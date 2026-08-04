import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import Editor from '@monaco-editor/react';

interface Problem {
    id: number;
    title: string;
    slug: string;
    description: string;
    difficulty: string;
    starter_code: Record<string, string>;
    examples: { input: string; output: string; explanation?: string }[];
}

const PracticeEditor: React.FC = () => {
    const { id } = useParams<{ id: string }>();
    const [problem, setProblem] = useState<Problem | null>(null);
    const [code, setCode] = useState<string>('// Select a language to start');
    const [language, setLanguage] = useState<string>('python');

    // Output States
    const [execStatus, setExecStatus] = useState<{ status: string; solved: boolean; message?: string } | null>(null);
    const [stdOut, setStdOut] = useState<string>('');
    const [stdErr, setStdErr] = useState<string>('');
    const [cmpOut, setCmpOut] = useState<string>('');

    const [loading, setLoading] = useState<boolean>(true);
    const [running, setRunning] = useState<boolean>(false);
    const [lastRunMode, setLastRunMode] = useState<'run' | 'submit' | null>(null);

    useEffect(() => {
        const fetchProblem = async () => {
            try {
                const response = await fetch(`http://localhost/ai-interview-project/backend/get_problem_details.php?id=${id}`);
                const data = await response.json();
                if (response.ok) {
                    setProblem(data);
                    // Set starter code for default language (python)
                    if (data.starter_code && data.starter_code.python) {
                        setCode(data.starter_code.python);
                    }
                } else {
                    console.error(`Error: ${data.message}`);
                }
            } catch (error) {
                console.error('Failed to fetch problem details.');
            } finally {
                setLoading(false);
            }
        };

        if (id) {
            fetchProblem();
        }
    }, [id]);

    const handleLanguageChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
        const lang = e.target.value;
        setLanguage(lang);
        if (problem && problem.starter_code && problem.starter_code[lang]) {
            setCode(problem.starter_code[lang]);
        } else {
            setCode('// No starter code available for this language.');
        }
    };

    const handleRunCode = async (mode: 'run' | 'submit') => {
        setRunning(true);
        setLastRunMode(mode);
        // Reset previous outputs
        setExecStatus(null);
        setStdOut('');
        setStdErr('');
        setCmpOut('');

        try {
            const response = await fetch('http://localhost/ai-interview-project/backend/run_code.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'include',
                body: JSON.stringify({
                    problem_id: id,
                    code: code,
                    language: language,
                    mode: mode
                }),
            });

            const result = await response.json().catch(() => {
                throw new Error("Invalid JSON response from server. Check console/network.");
            });

            setExecStatus({
                status: result.status || 'Unknown',
                solved: result.solved || false,
                message: result.message
            });
            setStdOut(result.stdout || '');
            setStdErr(result.stderr || '');
            setCmpOut(result.compile_output || '');

        } catch (error: any) {
            console.error("Run Code Error:", error);
            setExecStatus({ status: 'Error', solved: false });
            setStdErr(`Failed to run code. Details: ${error.message || 'Unknown error'}`);
        } finally {
            setRunning(false);
        }
    };

    if (loading) return <div className="p-8">Loading problem...</div>;
    if (!problem) return <div className="p-8">Problem not found.</div>;

    return (
        <div className="flex h-screen flex-col md:flex-row bg-gray-50">
            {/* Left: Description */}
            <div className="md:w-1/3 p-6 overflow-y-auto border-r border-gray-200 bg-white">
                <h1 className="text-2xl font-bold text-gray-900 mb-2">{problem.title}</h1>
                <div className="mb-4">
                    <span className={`px-2 py-1 text-xs font-semibold rounded ${problem.difficulty === 'Easy' ? 'bg-green-100 text-green-800' :
                        problem.difficulty === 'Medium' ? 'bg-yellow-100 text-yellow-800' :
                            'bg-red-100 text-red-800'
                        }`}>
                        {problem.difficulty}
                    </span>
                </div>
                <div className="prose prose-sm text-gray-700 mb-8">
                    <p>{problem.description}</p>
                </div>

                {/* Examples Section */}
                {problem.examples && problem.examples.length > 0 && (
                    <div className="mt-6">
                        <h2 className="text-lg font-semibold text-gray-900 mb-4">Examples</h2>
                        <div className="space-y-6">
                            {problem.examples.map((example, index) => (
                                <div key={index} className="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <p className="font-semibold text-gray-700 mb-2">Example {index + 1}:</p>
                                    <div className="space-y-2 text-sm font-mono">
                                        <div>
                                            <span className="font-bold text-gray-600">Input:</span>
                                            <div className="bg-gray-100 p-2 rounded mt-1 whitespace-pre-wrap">{example.input}</div>
                                        </div>
                                        <div>
                                            <span className="font-bold text-gray-600">Output:</span>
                                            <div className="bg-gray-100 p-2 rounded mt-1 whitespace-pre-wrap">{example.output}</div>
                                        </div>
                                        {example.explanation && (
                                            <div>
                                                <span className="font-bold text-gray-600">Explanation:</span>
                                                <div className="text-gray-700 mt-1 whitespace-pre-wrap font-sans">{example.explanation}</div>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                )}
            </div>

            {/* Right: Editor & Console */}
            <div className="md:w-2/3 flex flex-col h-full">
                {/* Toolbar */}
                <div className="bg-gray-100 border-b border-gray-200 p-2 flex items-center justify-between">
                    <div className="flex items-center space-x-2">
                        <select
                            value={language}
                            onChange={handleLanguageChange}
                            className="bg-white border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="c">C</option>
                            <option value="cpp">C++</option>
                            <option value="java">Java</option>
                            <option value="python">Python</option>
                            <option value="javascript">JavaScript</option>
                        </select>
                    </div>
                    <div className="flex space-x-2">
                        <button
                            onClick={() => handleRunCode('run')}
                            disabled={running}
                            className={`px-4 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 focus:outline-none disabled:opacity-50 ${running ? 'cursor-not-allowed' : ''}`}
                        >
                            {running && lastRunMode === 'run' ? 'Running...' : 'Run Code'}
                        </button>
                        <button
                            onClick={() => handleRunCode('submit')}
                            disabled={running}
                            className={`px-4 py-1 text-sm font-medium text-white bg-green-600 rounded hover:bg-green-700 focus:outline-none disabled:opacity-50 ${running ? 'cursor-not-allowed' : ''}`}
                        >
                            {running && lastRunMode === 'submit' ? 'Submitting...' : 'Submit'}
                        </button>
                    </div>
                </div>

                {/* Monaco Editor */}
                <div className="flex-grow flex flex-col overflow-hidden">
                    <Editor
                        height="60%"
                        language={language === 'c' || language === 'cpp' ? 'cpp' : language}
                        value={code}
                        theme="light"
                        onChange={(value) => setCode(value || '')}
                        options={{
                            minimap: { enabled: false },
                            fontSize: 14,
                            scrollBeyondLastLine: false,
                        }}
                    />

                    {/* Console/Output */}
                    <div className="h-[40%] bg-gray-900 text-white p-4 overflow-y-auto font-mono text-sm border-t border-gray-700">
                        {/* Status Bar - ONLY FOR SUBMIT */}
                        {execStatus && lastRunMode === 'submit' && (
                            <div className={`mb-3 p-2 rounded flex items-center justify-between ${execStatus.status === 'Solved' ? 'bg-green-900/30 text-green-300 border border-green-700' :
                                'bg-red-900/30 text-red-300 border border-red-700'
                                }`}>
                                <div className="flex items-center font-bold">
                                    <span className="mr-2">{execStatus.status === 'Solved' ? '✅' : '❌'}</span>
                                    {execStatus.status}
                                </div>
                                {execStatus.solved && <span className="text-yellow-400 font-semibold animate-pulse">🎉 Solved!</span>}
                            </div>
                        )}

                        <div className="text-gray-400 mb-2 uppercase text-xs font-bold tracking-wider">Console Output</div>

                        {/* Stdout */}
                        {stdOut ? (
                            <div className="mb-4">
                                <pre className="whitespace-pre-wrap break-words">{stdOut}</pre>
                            </div>
                        ) : null}

                        {/* Stderr (Runtime Errors) */}
                        {stdErr ? (
                            <div className="mb-4 p-2 bg-red-900/20 rounded border border-red-800">
                                <div className="text-red-400 mb-1 text-xs">Runtime Error:</div>
                                <pre className="whitespace-pre-wrap break-words text-red-300">{stdErr}</pre>
                            </div>
                        ) : null}

                        {/* Compile Errors */}
                        {cmpOut ? (
                            <div className="mb-4 p-2 bg-yellow-900/20 rounded border border-yellow-800">
                                <div className="text-yellow-400 mb-1 text-xs">Compilation Error:</div>
                                <pre className="whitespace-pre-wrap break-words text-yellow-300">{cmpOut}</pre>
                            </div>
                        ) : null}

                        {/* Message (System messages) */}
                        {execStatus?.message && !stdErr && !cmpOut && (
                            <div className="mb-4 text-gray-400 text-xs">
                                {execStatus.message}
                            </div>
                        )}

                        {!execStatus && !running && (
                            <div className="text-gray-500 italic">Run your code to see results here.</div>
                        )}
                        {running && (
                            <div className="text-gray-400 animate-pulse">Executing...</div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default PracticeEditor;
