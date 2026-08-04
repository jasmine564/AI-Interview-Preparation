import React, { useEffect, useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { FaCode, FaRobot, FaDatabase, FaShieldAlt, FaGamepad, FaCogs, FaNetworkWired, FaChartLine, FaProjectDiagram, FaArrowLeft } from 'react-icons/fa';

interface Role {
    id: number;
    title: string;
    description: string;
}

const RolesPage: React.FC = () => {
    const [roles, setRoles] = useState<Role[]>([]);
    const [loading, setLoading] = useState(true);
    const navigate = useNavigate();

    useEffect(() => {
        fetch('http://localhost/ai-interview-project/backend/get_roles.php', {
            credentials: 'include',
            headers: { 'Content-Type': 'application/json' }
        })
            .then(res => {
                if (res.status === 401) {
                    navigate('/login');
                    throw new Error("Unauthorized");
                }
                return res.json();
            })
            .then(data => {
                // DEFENSIVE: Ensure data is an array before setting state
                if (Array.isArray(data)) {
                    setRoles(data);
                } else {
                    console.error("API returned invalid format:", data);
                    setRoles([]); // Fallback to empty array
                }
                setLoading(false);
            })
            .catch(err => {
                console.error("Fetch error:", err);
                setRoles([]); // Fallback to empty array
                setLoading(false);
            });
    }, [navigate]);

    const getIcon = (title: string) => {
        const t = title.toLowerCase();
        if (t.includes('ai') || t.includes('prompt') || t.includes('robotics') || t.includes('ml')) return <FaRobot />;
        if (t.includes('security') || t.includes('hacker') || t.includes('soc')) return <FaShieldAlt />;
        if (t.includes('data') || t.includes('bi')) return <FaDatabase />;
        if (t.includes('game') || t.includes('vr') || t.includes('ar')) return <FaGamepad />;
        if (t.includes('embedded') || t.includes('systems')) return <FaCogs />;
        if (t.includes('network') || t.includes('api') || t.includes('blockchain') || t.includes('web3')) return <FaNetworkWired />;
        if (t.includes('manager') || t.includes('tpm')) return <FaProjectDiagram />;
        if (t.includes('analyst')) return <FaChartLine />;
        return <FaCode />;
    };

    const getGradient = (index: number) => {
        const gradients = [
            "from-blue-500 to-indigo-600",
            "from-indigo-500 to-purple-600",
            "from-purple-500 to-pink-600",
            "from-emerald-500 to-teal-600",
            "from-cyan-500 to-blue-600",
            "from-rose-500 to-orange-600",
            "from-amber-500 to-yellow-600"
        ];
        return gradients[index % gradients.length];
    };

    return (
        <div className="min-h-screen bg-slate-50 font-sans">
            <header className="bg-white border-b border-gray-100 sticky top-0 z-10 bg-opacity-90 backdrop-blur-sm">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                    <div className="flex items-center gap-4">
                        <Link to="/" className="p-2 -ml-2 rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition-colors">
                            <FaArrowLeft size={16} />
                        </Link>
                        <h1 className="text-xl font-bold text-slate-900 tracking-tight">AI Interview Prep</h1>
                    </div>
                </div>
            </header>

            <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div className="mb-12 text-center">
                    <h2 className="text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">Identify Your Role</h2>
                    <p className="text-lg text-slate-600 max-w-2xl mx-auto">Select a specialized track to begin your AI-driven mock interview. We have tailored questions for every technical domain.</p>
                </div>

                {loading ? (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {[1, 2, 3, 4, 5, 6].map(i => (
                            <div key={i} className="h-48 bg-white rounded-2xl shadow-sm animate-pulse"></div>
                        ))}
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        {Array.isArray(roles) && roles.map((role, idx) => (
                            <div
                                key={role.id}
                                onClick={() => navigate(`/session/${role.id}`, { state: { roleTitle: role.title } })}
                                className="group relative bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer overflow-hidden"
                            >
                                <div className={`absolute top-0 left-0 w-full h-1 bg-gradient-to-r ${getGradient(idx)}`}></div>

                                <div className="flex items-center justify-between mb-4">
                                    <div className={`p-3 rounded-lg bg-gray-50 text-gray-700 group-hover:text-white group-hover:bg-gradient-to-br ${getGradient(idx)} transition-colors duration-300`}>
                                        <span className="text-xl">
                                            {getIcon(role.title)}
                                        </span>
                                    </div>
                                    <span className="text-xs font-semibold px-2 py-1 bg-gray-100 text-gray-600 rounded-full group-hover:bg-white/90 group-hover:text-gray-800 transition-colors">
                                        Active
                                    </span>
                                </div>

                                <h3 className="text-lg font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">
                                    {role.title}
                                </h3>
                                <p className="text-sm text-slate-500 line-clamp-2 leading-relaxed">
                                    {role.description}
                                </p>

                                <div className="mt-6 flex items-center text-sm font-medium text-blue-600 opacity-0 transform translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                                    Start Session <span className="ml-1">→</span>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </main>
        </div>
    );
};

export default RolesPage;
