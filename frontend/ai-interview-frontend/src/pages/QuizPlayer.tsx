import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { FaCheckCircle, FaTimesCircle, FaArrowRight, FaRedo } from 'react-icons/fa';

interface Question {
    id: number;
    question_text: string;
    options: string[];
    correct_index: number;
    explanation: string;
}

interface QuizData {
    quiz: {
        title: string;
        description: string;
    };
    questions: Question[];
}

const QuizPlayer: React.FC = () => {
    const { id } = useParams();
    const navigate = useNavigate();
    const { user } = useAuth();

    const [data, setData] = useState<QuizData | null>(null);
    const [loading, setLoading] = useState(true);
    const [currentIndex, setCurrentIndex] = useState(0);
    const [score, setScore] = useState(0);
    const [showResult, setShowResult] = useState(false);

    // Question State
    const [selectedOption, setSelectedOption] = useState<number | null>(null);
    const [isAnswered, setIsAnswered] = useState(false);

    useEffect(() => {
        fetch(`http://localhost:8000/get_quiz_details.php?id=${id}`)
            .then(res => res.json())
            .then(resData => {
                if (resData.quiz && resData.questions) {
                    setData(resData);
                } else {
                    alert("Quiz not found!");
                    navigate('/quizzes');
                }
            })
            .catch(err => console.error(err))
            .finally(() => setLoading(false));
    }, [id, navigate]);

    const handleOptionClick = (idx: number) => {
        if (isAnswered) return;
        setSelectedOption(idx);
        setIsAnswered(true);

        // Update score if correct
        if (idx === data?.questions[currentIndex].correct_index) {
            setScore(prev => prev + 1);
        }
    };

    const handleNext = () => {
        if (!data) return;

        if (currentIndex < data.questions.length - 1) {
            setCurrentIndex(prev => prev + 1);
            setSelectedOption(null);
            setIsAnswered(false);
        } else {
            finishQuiz();
        }
    };

    const finishQuiz = async () => {
        setShowResult(true);
        if (!data) return;

        // Submit Score if user is logged in
        if (user) {
            try {
                await fetch('http://localhost:8000/submit_quiz.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        user_id: user.id,
                        quiz_id: id,
                        score: score,
                        total: data.questions.length
                    })
                });
            } catch (e) {
                console.error("Failed to save result", e);
            }
        }
    };

    if (loading || !data) return <div className="min-h-screen flex items-center justify-center text-gray-500">Loading Question...</div>;

    if (showResult) {
        const percentage = Math.round((score / data.questions.length) * 100);
        return (
            <div className="min-h-screen bg-gray-50 flex items-center justify-center px-4">
                <div className="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
                    <div className="w-24 h-24 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-6">
                        <span className="text-3xl font-bold text-blue-600">{percentage}%</span>
                    </div>
                    <h2 className="text-3xl font-bold text-gray-900 mb-2">Quiz Completed!</h2>
                    <p className="text-gray-600 mb-8">You scored {score} out of {data.questions.length}</p>

                    <button
                        onClick={() => navigate('/quizzes')}
                        className="w-full flex items-center justify-center gap-2 bg-gray-900 text-white px-6 py-3 rounded-xl font-medium hover:bg-black transition-colors"
                    >
                        <FaArrowRight /> Back to Quizzes
                    </button>
                    <button
                        onClick={() => window.location.reload()}
                        className="w-full mt-3 flex items-center justify-center gap-2 text-gray-600 px-6 py-3 rounded-xl font-medium hover:bg-gray-100 transition-colors"
                    >
                        <FaRedo /> Retry
                    </button>
                </div>
            </div>
        );
    }

    const currentQ = data.questions[currentIndex];

    return (
        <div className="min-h-screen bg-gray-100 flex flex-col items-center py-10 px-4 transition-all">
            <div className="w-full max-w-2xl">
                {/* Header */}
                <div className="mb-6 flex justify-between items-center text-sm font-medium text-gray-500 uppercase tracking-wide">
                    <span>{data.quiz.title}</span>
                    <span>Question {currentIndex + 1}/{data.questions.length}</span>
                </div>

                {/* Question Card */}
                <div className="bg-white rounded-2xl shadow-lg p-6 sm:p-10 transition-all">
                    <h2 className="text-xl sm:text-2xl font-bold text-gray-900 mb-8 leading-snug">
                        {currentQ.question_text}
                    </h2>

                    <div className="space-y-3">
                        {currentQ.options.map((option, idx) => {
                            let btnClass = "w-full text-left p-4 rounded-xl border-2 transition-all duration-200 font-medium ";

                            if (isAnswered) {
                                if (idx === currentQ.correct_index) {
                                    btnClass += "border-green-500 bg-green-50 text-green-700";
                                } else if (idx === selectedOption) {
                                    btnClass += "border-red-500 bg-red-50 text-red-700";
                                } else {
                                    btnClass += "border-gray-100 text-gray-400 opacity-50";
                                }
                            } else {
                                btnClass += "border-gray-200 hover:border-blue-500 hover:bg-blue-50 text-gray-700";
                            }

                            return (
                                <button
                                    key={idx}
                                    onClick={() => handleOptionClick(idx)}
                                    disabled={isAnswered}
                                    className={btnClass}
                                >
                                    <div className="flex justify-between items-center">
                                        <span>{option}</span>
                                        {isAnswered && idx === currentQ.correct_index && <FaCheckCircle className="text-green-500" />}
                                        {isAnswered && idx === selectedOption && idx !== currentQ.correct_index && <FaTimesCircle className="text-red-500" />}
                                    </div>
                                </button>
                            );
                        })}
                    </div>

                    {/* Explanation Box */}
                    {isAnswered && (
                        <div className="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-100 animate-fadeIn">
                            <h4 className="font-bold text-blue-800 text-sm mb-1 uppercase">Explanation</h4>
                            <p className="text-blue-900 text-sm leading-relaxed">{currentQ.explanation}</p>
                        </div>
                    )}

                    {/* Next Button */}
                    <div className="mt-8 flex justify-end">
                        <button
                            onClick={handleNext}
                            disabled={!isAnswered}
                            className={`px-8 py-3 rounded-full font-bold shadow-lg transition-transform transform ${isAnswered
                                    ? 'bg-blue-600 text-white hover:bg-blue-700 hover:-translate-y-1'
                                    : 'bg-gray-200 text-gray-400 cursor-not-allowed'
                                }`}
                        >
                            {currentIndex === data.questions.length - 1 ? 'Finish Quiz' : 'Next Question'}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default QuizPlayer;
