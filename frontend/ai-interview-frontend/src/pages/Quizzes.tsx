import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { FaCode, FaDatabase, FaLayerGroup } from 'react-icons/fa';

interface Quiz {
    id: number;
    title: string;
    description: string;
    difficulty: 'Easy' | 'Medium' | 'Hard';
    category: string;
    question_count: number;
}

const Quizzes: React.FC = () => {
    const navigate = useNavigate();
    const [quizzes, setQuizzes] = useState<Quiz[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetch('http://localhost:8000/get_quizzes.php')
            .then(res => res.json())
            .then(data => {
                if (Array.isArray(data)) setQuizzes(data);
            })
            .catch(err => console.error(err))
            .finally(() => setLoading(false));
    }, []);

    const getIcon = (cat: string) => {
        if (cat === 'Frontend') return <FaCode className="text-blue-500" />;
        if (cat === 'Backend') return <FaDatabase className="text-green-500" />;
        return <FaLayerGroup className="text-purple-500" />;
    };

    const getDifficultyColor = (diff: string) => {
        if (diff === 'Easy') return 'bg-green-100 text-green-800';
        if (diff === 'Medium') return 'bg-yellow-100 text-yellow-800';
        return 'bg-red-100 text-red-800';
    };

    return (
        <div className="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div className="max-w-7xl mx-auto">
                <div className="text-center mb-12">
                    <h1 className="text-4xl font-extrabold text-gray-900 sm:text-5xl">
                        Skill Quizzes
                    </h1>
                    <p className="mt-4 text-xl text-gray-600">
                        Test your knowledge with our technical assessments.
                    </p>
                </div>

                {loading ? (
                    <div className="text-center text-gray-500">Loading quizzes...</div>
                ) : (
                    <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        {quizzes.map((quiz) => (
                            <div
                                key={quiz.id}
                                onClick={() => navigate(`/quizzes/${quiz.id}`)}
                                className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow cursor-pointer border border-gray-100"
                            >
                                <div className="p-6">
                                    <div className="flex justify-between items-start">
                                        <div className="p-3 bg-gray-50 rounded-lg">
                                            {getIcon(quiz.category)}
                                        </div>
                                        <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getDifficultyColor(quiz.difficulty)}`}>
                                            {quiz.difficulty}
                                        </span>
                                    </div>
                                    <h3 className="mt-4 text-xl font-bold text-gray-900">{quiz.title}</h3>
                                    <p className="mt-2 text-gray-500 text-sm line-clamp-2">{quiz.description}</p>

                                    <div className="mt-6 flex items-center justify-between text-sm text-gray-500">
                                        <span>{quiz.question_count} Questions</span>
                                        <span className="text-blue-600 font-medium group-hover:underline">Start Quiz &rarr;</span>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </div>
    );
};

export default Quizzes;
