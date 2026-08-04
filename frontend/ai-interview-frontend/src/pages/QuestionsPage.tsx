import React, { useEffect, useState, useRef } from 'react';
import { useParams, Link, useNavigate, useLocation } from 'react-router-dom';
import LearnMorePanel from '../components/LearnMorePanel';
import { FaThumbtack, FaChevronDown, FaCode, FaArrowLeft, FaLightbulb } from 'react-icons/fa';

interface Question {
    id: string;
    question: string;
    answer: string;
    type?: 'theory' | 'coding';
    code?: string;
    source?: 'ai' | 'offline';
}

const QuestionsPage: React.FC = () => {
    const { roleId } = useParams<{ roleId: string }>();
    const navigate = useNavigate();
    const location = useLocation();

    // Immediate state from navigation (if available)
    const initialRoleTitle = location.state?.roleTitle || '';

    const [roleTitle, setRoleTitle] = useState(initialRoleTitle);
    const [questions, setQuestions] = useState<Question[]>([]);
    const [pinnedQuestions, setPinnedQuestions] = useState<Question[]>([]);
    const [loading, setLoading] = useState(true);
    const [page, setPage] = useState(1);
    const [loadingMore, setLoadingMore] = useState(false);

    // Accordion state
    const [expandedId, setExpandedId] = useState<string | null>(null);
    const [pinnedIds, setPinnedIds] = useState<string[]>([]);
    const listEndRef = useRef<HTMLDivElement>(null);

    // Panel state
    const [isPanelOpen, setIsPanelOpen] = useState(false);
    const [panelLoading, setPanelLoading] = useState(false);
    const [panelContent, setPanelContent] = useState('');
    const [panelTitle, setPanelTitle] = useState('');

    useEffect(() => {
        setPage(1);
        setQuestions([]);
        setPinnedQuestions([]);
        // Re-run fetch, passing the ID.
        // If we didn't have title from state, the API might give it to us.
        fetchQuestions(1);
    }, [roleId]);

    // Fetch pins whenever roleTitle is available
    useEffect(() => {
        if (roleTitle) {
            fetch(`http://localhost/ai-interview-project/backend/get_pins.php?role_title=${encodeURIComponent(roleTitle)}`, {
                credentials: 'include'
            })
                .then(res => res.json())
                .then(data => {
                    if (data.questions) {
                        setPinnedQuestions(data.questions);
                        setPinnedIds(data.questions.map((q: Question) => q.id));
                    }
                })
                .catch(err => console.error("Failed to fetch pins", err));
        }
    }, [roleTitle]);

    const fetchQuestions = (pageNum: number) => {
        const isFirstPage = pageNum === 1;
        if (isFirstPage) setLoading(true);
        else setLoadingMore(true);

        fetch(`http://localhost/ai-interview-project/backend/get_questions.php?role_id=${roleId}&page=${pageNum}`, {
            credentials: 'include'
        })
            .then(res => {
                if (res.status === 401) {
                    navigate('/login');
                    throw new Error("Unauthorized");
                }
                if (!res.ok) throw new Error("Failed to fetch questions");
                return res.json();
            })
            .then(data => {
                // If backend provides role title, update it (useful if navigating directly)
                if (data.role && !roleTitle) setRoleTitle(data.role);
                // Also update if different to ensure consistency
                if (data.role && roleTitle && data.role !== roleTitle) setRoleTitle(data.role);

                if (isFirstPage) {
                    setQuestions(data.questions);
                } else {
                    setQuestions(prev => {
                        const existingIds = new Set(prev.map(q => q.id));
                        const newQuestions = data.questions.filter((q: Question) => !existingIds.has(q.id));
                        return [...prev, ...newQuestions];
                    });
                }
                setLoading(false);
                setLoadingMore(false);
            })
            .catch(err => {
                console.error("Failed to fetch questions", err);
                setLoading(false);
                setLoadingMore(false);
            });
    };

    const handleLoadMore = () => {
        const nextPage = page + 1;
        setPage(nextPage);
        fetchQuestions(nextPage);
    };

    const toggleExpand = (id: string) => {
        setExpandedId(prev => (prev === id ? null : id));
    };

    const togglePin = (e: React.MouseEvent, question: Question) => {
        e.stopPropagation();

        // Optimistic update
        const id = question.id;
        const isCurrentlyPinned = pinnedIds.includes(id);

        if (isCurrentlyPinned) {
            setPinnedIds(prev => prev.filter(p => p !== id));
            // Also remove from pinnedQuestions if it's there
            setPinnedQuestions(prev => prev.filter(q => q.id !== id));
        } else {
            setPinnedIds(prev => [...prev, id]);
            // Optimistically add to pinnedQuestions, but maybe wait for reload to sort to top?
            // The user requirement says "reopen same role -> pin must appear at TOP".
            // It doesn't strictly say it must jump to top *immediately* upon clicking.
            // But we should probably keep it consistent. For now, let's just track ID persistence.
        }

        // Backend Sync
        fetch('http://localhost/ai-interview-project/backend/save_pin.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify({
                role_title: roleTitle,
                question_id: id,
                question_data: question
            })
        })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    console.error("Pin error:", data.error);
                    // Revert on error
                    if (isCurrentlyPinned) {
                        setPinnedIds(prev => [...prev, id]);
                    } else {
                        setPinnedIds(prev => prev.filter(p => p !== id));
                    }
                }
            })
            .catch(err => {
                console.error("Pin request failed", err);
                // Revert on error
                if (isCurrentlyPinned) {
                    setPinnedIds(prev => [...prev, id]);
                } else {
                    setPinnedIds(prev => prev.filter(p => p !== id));
                }
            });
    };

    const handleLearnMore = (question: string) => {
        setPanelTitle(question);
        setPanelContent('');
        setPanelLoading(true);
        setIsPanelOpen(true);

        fetch('http://localhost/ai-interview-project/backend/learn_more.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify({
                question: question,
                role: roleTitle
            })
        })
            .then(res => res.json())
            .then(data => {
                setPanelContent(data.content || "No detailed explanation available.");
                setPanelLoading(false);
            })
            .catch(err => {
                console.error("Error fetching explanation", err);
                setPanelContent("Failed to load explanation.");
                setPanelLoading(false);
            });
    };

    // MERGE LISTS FOR DISPLAY
    // 1. Pinned questions at top (filtered by current pinnedIds)
    // 2. Initial questions (excluding ones that are already in the Top Section)

    // Top Section: pinnedQuestions (filtered by current pinnedIds)
    const topQuestions = pinnedQuestions.filter(q => pinnedIds.includes(q.id));
    const topIds = new Set(topQuestions.map(q => q.id));
    const bottomQuestions = questions.filter(q => !topIds.has(q.id));

    const displayList = [...topQuestions, ...bottomQuestions];

    return (
        <div className="min-h-screen bg-slate-50 flex flex-col font-sans text-slate-900">
            {/* Premium Header */}
            <header className="bg-white/80 backdrop-blur-md sticky top-0 z-30 border-b border-gray-200">
                <div className="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
                    <div className="flex items-center gap-4">
                        <Link to="/sessions" className="p-2 -ml-2 rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition-colors">
                            <FaArrowLeft size={16} />
                        </Link>
                        <div>
                            <div className="flex items-center gap-2">
                                <h1 className="text-lg font-bold text-slate-900 tracking-tight">
                                    {roleTitle ? roleTitle : 'Loading...'}
                                </h1>
                                <span className="px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 text-xs font-semibold uppercase tracking-wider">
                                    Interview
                                </span>
                            </div>
                            <p className="text-sm text-slate-500">AI-Guided Session</p>
                        </div>
                    </div>
                </div>
            </header>

            <main className="flex-1 max-w-4xl mx-auto px-4 py-8 w-full pb-32">
                {loading ? (
                    <div className="space-y-6">
                        <div className="flex items-center gap-2 text-slate-500 text-sm mb-4 px-1">
                            <div className="w-2 h-2 bg-blue-500 rounded-full animate-ping"></div>
                            AI is crafting unique questions for this role...
                        </div>
                        {[1, 2, 3].map(i => (
                            <div key={i} className="bg-white rounded-xl h-32 animate-pulse shadow-sm border border-gray-100"></div>
                        ))}
                    </div>
                ) : (
                    <div className="space-y-6">
                        {displayList.map((q, idx) => {
                            const isExpanded = expandedId === q.id;
                            const isPinned = pinnedIds.includes(q.id);
                            return (
                                <div
                                    key={q.id}
                                    className={`group bg-white rounded-2xl transition-all duration-300 overflow-hidden ${isExpanded ? 'shadow-lg ring-1 ring-black/5' : 'shadow-sm hover:shadow-md border border-gray-100'} ${isPinned ? 'border-l-4 border-l-amber-400' : ''}`}
                                >
                                    <div
                                        className="p-6 cursor-pointer flex items-start gap-4 select-none"
                                        onClick={() => toggleExpand(q.id)}
                                    >
                                        <div
                                            className={`mt-1 transition-colors ${isPinned ? 'text-amber-500' : 'text-gray-300 group-hover:text-gray-400'}`}
                                            title={isPinned ? "Unpin" : "Pin for later"}
                                            onClick={(e) => togglePin(e, q)}
                                        >
                                            <FaThumbtack size={14} className={isPinned ? "" : "transform rotate-45"} />
                                        </div>

                                        <div className="flex-1">
                                            <div className="flex items-center gap-2 mb-2">
                                                <span className="text-xs font-mono text-gray-400">0{idx + 1}</span>
                                                {q.type === 'coding' && (
                                                    <span className="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-600 uppercase tracking-wider">
                                                        <FaCode className="mr-1" size={10} /> Code
                                                    </span>
                                                )}
                                                {q.type !== 'coding' && (
                                                    <span className="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-600 uppercase tracking-wider">
                                                        Theoretical
                                                    </span>
                                                )}
                                                {q.source === 'offline' && (
                                                    <span className="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-200 text-gray-600 uppercase tracking-wider">
                                                        Offline Mode
                                                    </span>
                                                )}
                                                {isPinned && (
                                                    <span className="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-600 uppercase tracking-wider">
                                                        Pinned
                                                    </span>
                                                )}
                                            </div>
                                            <h3 className={`text-lg font-semibold leading-snug transition-colors ${isExpanded ? 'text-blue-700' : 'text-slate-800'}`}>
                                                {q.question.replace(/^Question \d+:?/, '').replace(/^Scenario:?/, '')}
                                            </h3>
                                        </div>

                                        <div className={`mt-1 text-gray-400 transition-transform duration-300 ${isExpanded ? 'rotate-180 text-blue-500' : ''}`}>
                                            <FaChevronDown size={14} />
                                        </div>
                                    </div>

                                    <div className={`transition-all duration-500 ease-in-out ${isExpanded ? 'max-h-[2000px] opacity-100' : 'max-h-0 opacity-0'}`}>
                                        <div className="px-6 pb-6 pl-12 sm:pl-14">
                                            {q.code && (
                                                <div className="mb-6 rounded-lg overflow-hidden border border-slate-700 shadow-inner">
                                                    <div className="bg-slate-800 px-4 py-2 text-xs text-slate-400 font-mono flex items-center gap-2">
                                                        <div className="flex gap-1.5">
                                                            <div className="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                                                            <div className="w-2.5 h-2.5 rounded-full bg-yellow-500"></div>
                                                            <div className="w-2.5 h-2.5 rounded-full bg-green-500"></div>
                                                        </div>
                                                        <span>editor.js</span>
                                                    </div>
                                                    <pre className="bg-slate-900 p-4 overflow-x-auto">
                                                        <code className="text-sm font-mono text-emerald-400 leading-relaxed">{q.code}</code>
                                                    </pre>
                                                </div>
                                            )}

                                            <div className="prose prose-sm max-w-none text-slate-600 mb-6 leading-relaxed">
                                                <div className="flex gap-2">
                                                    <div className="w-1 bg-blue-200 rounded-full flex-shrink-0"></div>
                                                    <p className="m-0 mb-4">{q.answer}</p>
                                                </div>
                                            </div>

                                            <div className="flex justify-end border-t border-gray-50 pt-4">
                                                <button
                                                    onClick={(e) => {
                                                        e.stopPropagation();
                                                        handleLearnMore(q.question);
                                                    }}
                                                    className="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 text-white text-sm font-medium rounded-lg hover:bg-black transition-all shadow-md hover:shadow-lg active:scale-95"
                                                >
                                                    <FaLightbulb className="text-yellow-300" />
                                                    <span>AI Deep Dive</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                        <div ref={listEndRef} />
                    </div>
                )}

                {/* Load More Trigger */}
                {!loading && (
                    <div className="mt-12 flex justify-center items-center gap-4 pointer-events-auto">
                        <button
                            onClick={handleLoadMore}
                            disabled={loadingMore}
                            className="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 text-slate-600 font-medium rounded-full hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm focus:ring-4 focus:ring-gray-100 disabled:opacity-50"
                        >
                            {loadingMore ? 'Thinking...' : 'Load More Questions'}
                        </button>
                        <Link
                            to="/prepare"
                            className="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-medium rounded-full hover:bg-blue-700 transition-all shadow-md hover:shadow-lg active:scale-95"
                        >
                            Start Practice
                        </Link>
                    </div>
                )}
            </main>

            <LearnMorePanel
                isOpen={isPanelOpen}
                onClose={() => setIsPanelOpen(false)}
                title={panelTitle}
                content={panelContent}
                loading={panelLoading}
            />
        </div>
    );
};

export default QuestionsPage;
