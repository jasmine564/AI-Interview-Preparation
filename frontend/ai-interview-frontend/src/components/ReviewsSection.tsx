import React, { useEffect, useState } from 'react';
import { FaStar, FaQuoteLeft, FaUserCircle } from 'react-icons/fa';

interface Review {
    user_name: string;
    rating: number;
    feedback_text: string;
}

const ReviewsSection = () => {
    const [reviews, setReviews] = useState<Review[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetch('http://localhost:8000/get_reviews.php')
            .then(res => res.json())
            .then(data => {
                if (Array.isArray(data)) setReviews(data);
            })
            .catch(err => console.error("Failed to load reviews", err))
            .finally(() => setLoading(false));
    }, []);

    if (!loading && reviews.length === 0) return null;

    return (
        <div className="mt-20 w-full max-w-7xl mx-auto px-4 pb-12">
            <div className="flex items-center gap-3 mb-10">
                <div className="h-8 w-1.5 bg-gradient-to-b from-blue-500 to-purple-500 rounded-full"></div>
                <h2 className="text-3xl font-bold text-gray-100 tracking-tight">Community Stories</h2>
            </div>

            {loading ? (
                <div className="text-gray-500 text-center py-8">Loading reviews...</div>
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {reviews.map((rev, idx) => (
                        <div key={idx} className="bg-gray-900/40 backdrop-blur-sm border border-gray-800 p-6 rounded-2xl hover:bg-gray-900/60 hover:border-gray-700 transition-all duration-300 group hover:-translate-y-1">
                            <div className="flex justify-between items-start mb-4">
                                <div className="flex gap-1 text-yellow-500 text-sm">
                                    {[...Array(rev.rating)].map((_, i) => <FaStar key={i} />)}
                                </div>
                                <FaQuoteLeft className="text-gray-700 group-hover:text-blue-500/50 transition-colors text-xl" />
                            </div>

                            <p className="text-gray-300 mb-6 italic leading-relaxed text-sm min-h-[60px]">"{rev.feedback_text}"</p>

                            <div className="flex items-center gap-3 border-t border-gray-800 pt-4">
                                <div className="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-purple-600 flex items-center justify-center text-xs font-bold text-white shadow-lg">
                                    {rev.user_name ? rev.user_name.charAt(0).toUpperCase() : <FaUserCircle />}
                                </div>
                                <div className="flex flex-col">
                                    <span className="text-sm font-medium text-gray-200">{rev.user_name}</span>
                                    <span className="text-xs text-gray-500">Verified User</span>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
};

export default ReviewsSection;
