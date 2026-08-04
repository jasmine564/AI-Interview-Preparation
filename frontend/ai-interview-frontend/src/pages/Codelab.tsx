import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';

interface Problem {
    id: number;
    title: string;
    difficulty: string;
    topic: string;
    is_solved: number; // 0 or 1
}

const Codelab: React.FC = () => {
    const [problems, setProblems] = useState<Problem[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetch('http://localhost/ai-interview-project/backend/get_problems.php', { credentials: 'include' })
            .then(res => res.json())
            .then(data => {
                if (Array.isArray(data)) {
                    setProblems(data);
                } else {
                    console.error("API Error:", data);
                }
                setLoading(false);
            })
            .catch(err => {
                console.error(err);
                setLoading(false);
            });
    }, []);

    const solvedCount = problems.filter(p => p.is_solved).length;
    const totalCount = problems.length;
    const progress = totalCount > 0 ? (solvedCount / totalCount) * 100 : 0;

    return (
        <div className="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div className="max-w-5xl mx-auto">
                <div className="text-center mb-8">
                    <h1 className="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                        Codelab - Coding Challenges
                    </h1>
                    <p className="mt-4 text-xl text-gray-500">
                        Sharpen your skills with our curated list of challenges.
                    </p>
                </div>

                {/* Progress Bar */}
                {!loading && (
                    <div className="mb-8 bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center justify-between">
                        <div>
                            <h2 className="text-lg font-semibold text-gray-800">Your Progress</h2>
                            <p className="text-sm text-gray-500">Keep solving to master the interview!</p>
                        </div>
                        <div className="flex items-center space-x-4">
                            <div className="text-right">
                                <span className="text-2xl font-bold text-gray-900">{solvedCount}</span>
                                <span className="text-gray-500"> / {totalCount} Solved</span>
                            </div>
                            <div className="w-32 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div className="h-full bg-green-500" style={{ width: `${progress}%` }}></div>
                            </div>
                        </div>
                    </div>
                )}

                <div className="bg-white shadow overflow-hidden sm:rounded-md border border-gray-200">
                    {loading ? (
                        <div className="p-8 text-center text-gray-500">Loading problems...</div>
                    ) : (
                        <ul className="divide-y divide-gray-200">
                            {problems.map((problem) => (
                                <li key={problem.id}>
                                    <Link to={`/practice/${problem.id}`} className="block hover:bg-gray-50 transition duration-150 ease-in-out group">
                                        <div className="px-4 py-4 sm:px-6 flex items-center justify-between">
                                            <div className="flex items-center space-x-4">
                                                {/* Solved Status */}
                                                <div className="flex-shrink-0">
                                                    {problem.is_solved ? (
                                                        <span className="h-6 w-6 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                                            <svg className="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" /></svg>
                                                        </span>
                                                    ) : (
                                                        <span className="h-6 w-6 rounded-full border-2 border-gray-200"></span>
                                                    )}
                                                </div>

                                                <div>
                                                    <div className="flex items-center space-x-2">
                                                        <p className="text-lg font-medium text-blue-600 truncate group-hover:underline">{problem.title}</p>
                                                        <span className={`px-2 py-0.5 text-xs font-medium rounded-full ${problem.difficulty === 'Easy' ? 'bg-green-100 text-green-800' :
                                                            problem.difficulty === 'Medium' ? 'bg-yellow-100 text-yellow-800' :
                                                                'bg-red-100 text-red-800'
                                                            }`}>
                                                            {problem.difficulty}
                                                        </span>
                                                    </div>
                                                    <div className="mt-1">
                                                        <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                            {problem.topic || 'General'}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="flex items-center">
                                                <svg className="h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fillRule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clipRule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    )}
                </div>
            </div>
        </div>
    );
};

export default Codelab;
