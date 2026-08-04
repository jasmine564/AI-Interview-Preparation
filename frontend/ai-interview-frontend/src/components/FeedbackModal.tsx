import React, { useState } from 'react';
import { FaStar } from 'react-icons/fa';

interface FeedbackModalProps {
    isOpen: boolean;
    onClose: () => void;
    userParams?: {
        userId?: number;
        userName?: string;
    };
}

const FeedbackModal: React.FC<FeedbackModalProps> = ({ isOpen, onClose, userParams }) => {
    const [rating, setRating] = useState<number>(0);
    const [hover, setHover] = useState<number>(0);
    const [text, setText] = useState('');
    const [submitting, setSubmitting] = useState(false);

    if (!isOpen) return null;

    const handleSubmit = async () => {
        if (rating === 0) return alert("Please select a star rating.");

        setSubmitting(true);
        try {
            const res = await fetch('http://localhost:8000/submit_feedback.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    user_id: userParams?.userId,
                    user_name: userParams?.userName || 'Anonymous',
                    rating,
                    text,
                    category: 'general'
                })
            });
            const data = await res.json();
            if (data.success) {
                alert("Thanks for your feedback!");
                onClose();
                setRating(0);
                setText('');
            } else {
                alert("Error: " + (data.error || 'Unknown error'));
            }
        } catch (e) {
            console.error(e);
            alert("Failed to submit feedback.");
        } finally {
            setSubmitting(false);
        }
    };

    return (
        <div className="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm animate-fadeIn">
            <div className="bg-gray-900 border border-gray-700 p-6 rounded-2xl shadow-2xl w-full max-w-md relative transform transition-all scale-100">
                <button
                    onClick={onClose}
                    className="absolute top-4 right-4 text-gray-400 hover:text-white hover:bg-gray-800 rounded-full p-1 transition-colors"
                >
                    ✕
                </button>

                <h2 className="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent mb-2 text-center">
                    We value your feedback
                </h2>
                <p className="text-gray-400 text-center text-sm mb-6">How was your experience?</p>

                {/* Star Rating */}
                <div className="flex justify-center gap-3 mb-6">
                    {[1, 2, 3, 4, 5].map((star) => (
                        <button
                            key={star}
                            type="button"
                            className="focus:outline-none transition-transform hover:scale-125 duration-200"
                            onClick={() => setRating(star)}
                            onMouseEnter={() => setHover(star)}
                            onMouseLeave={() => setHover(rating)}
                        >
                            <FaStar
                                size={36}
                                className={`transition-colors duration-200 ${star <= (hover || rating) ? "text-yellow-400 drop-shadow-[0_0_8px_rgba(250,204,21,0.5)]" : "text-gray-700"}`}
                            />
                        </button>
                    ))}
                </div>

                <textarea
                    className="w-full bg-gray-800 border border-gray-700 rounded-xl p-4 text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none resize-none h-32 mb-6 placeholder-gray-500 transition-all"
                    placeholder="Tell us what you liked or how we can improve..."
                    value={text}
                    onChange={(e) => setText(e.target.value)}
                />

                <div className="flex justify-end gap-3">
                    <button
                        onClick={onClose}
                        className="px-4 py-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        onClick={handleSubmit}
                        disabled={submitting}
                        className="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-semibold rounded-xl transition-all shadow-lg shadow-blue-900/20 disabled:opacity-50 disabled:cursor-not-allowed transform active:scale-95"
                    >
                        {submitting ? 'Sending...' : 'Submit Review'}
                    </button>
                </div>
            </div>
        </div>
    );
};

export default FeedbackModal;
