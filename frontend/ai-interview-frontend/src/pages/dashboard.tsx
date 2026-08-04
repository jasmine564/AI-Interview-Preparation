import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

import FeedbackModal from '../components/FeedbackModal';
import ThemeToggle from '../components/ThemeToggle';
import { FaFileUpload, FaChartLine, FaBriefcase, FaMicrophone, FaCode, FaClipboardList, FaArrowRight, FaTwitter, FaLinkedin, FaGithub, FaEnvelope, FaComment } from 'react-icons/fa';

const Dashboard: React.FC = () => {
    const navigate = useNavigate();
    const { user, logout } = useAuth();
    const [isFeedbackOpen, setFeedbackOpen] = useState(false);

    return (
        <div className="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-50 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 font-sans text-slate-900 dark:text-slate-100 selection:bg-indigo-100/50 relative overflow-hidden">


            <FeedbackModal
                isOpen={isFeedbackOpen}
                onClose={() => setFeedbackOpen(false)}
                userParams={{ userId: user?.id, userName: user?.full_name }}
            />

            {/* Navbar */}
            <nav className="fixed w-full z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 transition-all duration-300">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-20 items-center">
                        <div className="flex-shrink-0 flex items-center group cursor-pointer">
                            {/* Logo Icon */}
                            <div className="bg-blue-50 p-2 rounded-xl group-hover:bg-blue-100 transition-colors duration-300">
                                <svg className="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                            <span className="ml-3 font-bold text-xl text-slate-800 dark:text-slate-100 tracking-tight group-hover:text-blue-600 transition-colors">AI Interview Prep</span>
                        </div>
                        <div className="hidden md:flex space-x-1">
                            <Link to="/" className="text-blue-700 bg-blue-50 px-4 py-2 rounded-lg transition-all duration-200 font-semibold text-sm lg:text-sm shadow-sm border border-blue-100">Home</Link>
                            <Link to="/prepare" className="text-slate-600 hover:text-blue-600 hover:bg-blue-50/50 px-4 py-2 rounded-lg transition-all duration-200 font-medium text-sm lg:text-sm">Practice</Link>
                            <Link to="/quizzes" className="text-slate-600 hover:text-blue-600 hover:bg-blue-50/50 px-4 py-2 rounded-lg transition-all duration-200 font-medium text-sm lg:text-sm">Quizzes</Link>
                            <Link to="/codelab" className="text-slate-600 hover:text-blue-600 hover:bg-blue-50/50 px-4 py-2 rounded-lg transition-all duration-200 font-medium text-sm lg:text-sm">Codelab</Link>
                            <Link to="/sessions" className="text-slate-600 hover:text-blue-600 hover:bg-blue-50/50 px-4 py-2 rounded-lg transition-all duration-200 font-medium text-sm lg:text-sm">Sessions</Link>
                        </div>

                        <div className="flex items-center space-x-3">
                            <ThemeToggle />
                            {user ? (
                                <div className="flex items-center space-x-3">

                                    <span className="text-slate-700 font-medium text-sm border-l border-slate-200 pl-4 ml-1">Hi, {user.full_name}</span>
                                    <button
                                        onClick={async () => {
                                            await logout();
                                            navigate('/login');
                                        }}
                                        className="text-slate-500 hover:text-red-600 font-medium px-3 py-2 rounded-md transition text-sm hover:bg-red-50"
                                    >
                                        Logout
                                    </button>
                                </div>
                            ) : (
                                <>
                                    <Link to="/login" className="text-slate-700 hover:text-blue-600 font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 text-sm">Login</Link>
                                    <Link to="/register" className="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 transform hover:-translate-y-0.5 active:translate-y-0 text-sm">Get Started</Link>
                                </>
                            )}
                        </div>
                    </div>
                </div>
            </nav>

            {/* Hero Section */}
            <main className="pt-32 pb-16 lg:pt-40 lg:pb-24">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

                    {/* Centered Content */}
                    <div className="space-y-8 animate-fade-in-up relative z-10">
                        <div className="inline-flex items-center px-4 py-1.5 rounded-full bg-white/80 backdrop-blur-sm text-slate-600 text-sm font-medium mb-4 border border-slate-200 shadow-sm">
                            <span className="text-[#5b5df5] mr-2">✨</span>
                            AI-Powered Career Preparation
                        </div>

                        <h1 className="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-[#1a1b2e] dark:text-white leading-[1.1] tracking-tight mb-6">
                            Land Your Dream Job with <br className="hidden sm:block" />
                            <span className="text-transparent bg-clip-text bg-gradient-to-r from-[#5b5df5] to-[#8b5cf6]">AI Precision</span>
                        </h1>
                        <p className="text-lg sm:text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto leading-relaxed font-normal">
                            Upload your resume and let our AI guide you through personalized preparation, practice sessions, and skill assessments tailored to your target roles.
                        </p>

                        <div className="flex flex-col sm:flex-row justify-center gap-4 pt-8">
                            <Link to="/resume-booster" className="inline-flex justify-center items-center px-8 py-4 text-base font-semibold rounded-xl text-white bg-[#5b5df5] hover:bg-[#4a4ce6] shadow-xl shadow-indigo-500/20 hover:shadow-indigo-600/30 transition-all transform hover:-translate-y-1 active:scale-95 min-w-[180px]">
                                <svg className="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Upload Resume
                            </Link>
                            <button
                                onClick={() => {
                                    document.getElementById('how-it-works')?.scrollIntoView({ behavior: 'smooth' });
                                }}
                                className="inline-flex justify-center items-center px-8 py-4 text-base font-semibold rounded-xl text-[#333] bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all min-w-[180px]"
                            >
                                Learn How It Works <span className="ml-2">→</span>
                            </button>
                        </div>

                        {/* Stats Row - Matches Image 1 */}


                    </div>
                </div>

                {/* Feature Cards - Matches Image 2 */}
                <div className="w-full bg-white dark:bg-slate-900 py-16 lg:py-24 lg:mt-24 border-y border-slate-50 dark:border-slate-800 relative z-20">
                    <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-10 text-left">
                            {/* Card 1 */}
                            <div className="bg-white dark:bg-slate-800 p-10 rounded-[2.5rem] shadow-[0_20px_50px_rgba(59,130,246,0.03)] hover:shadow-[0_30px_60px_rgba(59,130,246,0.08)] border border-slate-50 dark:border-slate-700 transition-all duration-500 hover:-translate-y-2 group relative overflow-hidden">
                                <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-indigo-500/10 to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-700"></div>
                                <div className="w-14 h-14 bg-[#6366f1] rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-indigo-500/20 group-hover:scale-110 transition-transform duration-500">
                                    <svg className="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <h3 className="text-xl font-bold text-[#1e293b] dark:text-white mb-4 group-hover:text-[#6366f1] transition-colors duration-300">AI-Powered Analysis</h3>
                                <p className="text-slate-500 dark:text-slate-400 leading-relaxed text-[0.95rem] font-medium">
                                    Advanced AI evaluates your resume against industry standards to ensure you stand out.
                                </p>
                            </div>

                            {/* Card 2 */}
                            <div className="bg-white dark:bg-slate-800 p-10 rounded-[2.5rem] shadow-[0_20px_50px_rgba(59,130,246,0.03)] hover:shadow-[0_30px_60px_rgba(59,130,246,0.08)] border border-slate-50 dark:border-slate-700 transition-all duration-500 hover:-translate-y-2 group relative overflow-hidden">
                                <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-indigo-500/10 to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-700"></div>
                                <div className="w-14 h-14 bg-[#6366f1] rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-indigo-500/20 group-hover:scale-110 transition-transform duration-500">
                                    <svg className="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                                <h3 className="text-xl font-bold text-[#1e293b] dark:text-white mb-4 group-hover:text-[#6366f1] transition-colors duration-300">Personalized Path</h3>
                                <p className="text-slate-500 dark:text-slate-400 leading-relaxed text-[0.95rem] font-medium">
                                    AI generates role-specific mock tests based on your custom job description.
                                </p>
                            </div>

                            {/* Card 3 */}
                            <div className="bg-white dark:bg-slate-800 p-10 rounded-[2.5rem] shadow-[0_20px_50px_rgba(59,130,246,0.03)] hover:shadow-[0_30px_60px_rgba(59,130,246,0.08)] border border-slate-50 dark:border-slate-700 transition-all duration-500 hover:-translate-y-2 group relative overflow-hidden">
                                <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-indigo-500/10 to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-700"></div>
                                <div className="w-14 h-14 bg-[#6366f1] rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-indigo-500/20 group-hover:scale-110 transition-transform duration-500">
                                    <svg className="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <h3 className="text-xl font-bold text-[#1e293b] dark:text-white mb-4 group-hover:text-[#6366f1] transition-colors duration-300">Interview Ready</h3>
                                <p className="text-slate-500 dark:text-slate-400 leading-relaxed text-[0.95rem] font-medium">
                                    Practice with real questions and get instant feedback to build confidence.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* How It Works Section */}
                <div id="how-it-works" className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
                    <div className="text-center mb-20">
                        <h2 className="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-6">How It Works</h2>
                        <p className="text-lg text-slate-600 dark:text-slate-300 max-w-2xl mx-auto font-medium">Follow our guided process to transform your career preparation</p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12 px-4">
                        {/* Step 1 */}
                        <div className="relative bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_60px_rgba(0,0,0,0.08)] transition-all duration-300 hover:-translate-y-1 group">
                            <div className="absolute -top-5 -left-5 w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-lg shadow-blue-500/30 transform -rotate-6 group-hover:rotate-0 transition-transform duration-300">1</div>
                            <div className="w-14 h-14 bg-blue-50 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400 mb-6 text-2xl group-hover:scale-110 transition-transform duration-300">
                                <FaFileUpload />
                            </div>
                            <h3 className="text-xl font-bold text-slate-900 dark:text-white mb-3">Upload Resume</h3>
                            <p className="text-slate-500 dark:text-slate-400 leading-relaxed font-medium">
                                Upload your resume to let AI understand your skills, experience, and gaps.
                            </p>
                        </div>

                        {/* Step 2 */}
                        <div className="relative bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_60px_rgba(0,0,0,0.08)] transition-all duration-300 hover:-translate-y-1 group">
                            <div className="absolute -top-5 -left-5 w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-lg shadow-blue-500/30 transform -rotate-6 group-hover:rotate-0 transition-transform duration-300">2</div>
                            <div className="w-14 h-14 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-6 text-2xl group-hover:scale-110 transition-transform duration-300">
                                <FaChartLine />
                            </div>
                            <h3 className="text-xl font-bold text-slate-900 dark:text-white mb-3">Get AI Analysis</h3>
                            <p className="text-slate-500 dark:text-slate-400 leading-relaxed font-medium">
                                AI analyzes your resume against job-ready standards and interview expectations.
                            </p>
                        </div>

                        {/* Step 3 */}
                        <div className="relative bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_60px_rgba(0,0,0,0.08)] transition-all duration-300 hover:-translate-y-1 group">
                            <div className="absolute -top-5 -left-5 w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-lg shadow-blue-500/30 transform -rotate-6 group-hover:rotate-0 transition-transform duration-300">3</div>
                            <div className="w-14 h-14 bg-violet-50 dark:bg-violet-900/30 rounded-2xl flex items-center justify-center text-violet-600 dark:text-violet-400 mb-6 text-2xl group-hover:scale-110 transition-transform duration-300">
                                <FaBriefcase />
                            </div>
                            <h3 className="text-xl font-bold text-slate-900 dark:text-white mb-3">Explore Job Roles</h3>
                            <p className="text-slate-500 dark:text-slate-400 leading-relaxed font-medium">
                                Explore career roles that best match your profile and interests.
                            </p>
                        </div>

                        {/* Step 4 */}
                        <div className="relative bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_60px_rgba(0,0,0,0.08)] transition-all duration-300 hover:-translate-y-1 group">
                            <div className="absolute -top-5 -left-5 w-12 h-12 rounded-2xl bg-purple-600 text-white flex items-center justify-center font-bold text-lg shadow-lg shadow-purple-500/30 transform -rotate-6 group-hover:rotate-0 transition-transform duration-300">4</div>
                            <div className="w-14 h-14 bg-purple-50 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center text-purple-600 dark:text-purple-400 mb-6 text-2xl group-hover:scale-110 transition-transform duration-300">
                                <FaMicrophone />
                            </div>
                            <h3 className="text-xl font-bold text-slate-900 dark:text-white mb-3">Prepare</h3>
                            <p className="text-slate-500 dark:text-slate-400 leading-relaxed font-medium">
                                Enter any custom job description to get role-specific mock tests.
                            </p>
                        </div>

                        {/* Step 5 */}
                        <div className="relative bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_60px_rgba(0,0,0,0.08)] transition-all duration-300 hover:-translate-y-1 group">
                            <div className="absolute -top-5 -left-5 w-12 h-12 rounded-2xl bg-purple-600 text-white flex items-center justify-center font-bold text-lg shadow-lg shadow-purple-500/30 transform -rotate-6 group-hover:rotate-0 transition-transform duration-300">5</div>
                            <div className="w-14 h-14 bg-fuchsia-50 dark:bg-fuchsia-900/30 rounded-2xl flex items-center justify-center text-fuchsia-600 dark:text-fuchsia-400 mb-6 text-2xl group-hover:scale-110 transition-transform duration-300">
                                <FaCode />
                            </div>
                            <h3 className="text-xl font-bold text-slate-900 dark:text-white mb-3">CodeLab</h3>
                            <p className="text-slate-500 dark:text-slate-400 leading-relaxed font-medium">
                                Solve coding interview questions using an inbuilt code editor with real-world scenarios.
                            </p>
                        </div>

                        {/* Step 6 */}
                        <div className="relative bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-[0_10px_40px_rgba(0,0,0,0.04)] hover:shadow-[0_20px_60px_rgba(0,0,0,0.08)] transition-all duration-300 hover:-translate-y-1 group">
                            <div className="absolute -top-5 -left-5 w-12 h-12 rounded-2xl bg-purple-600 text-white flex items-center justify-center font-bold text-lg shadow-lg shadow-purple-500/30 transform -rotate-6 group-hover:rotate-0 transition-transform duration-300">6</div>
                            <div className="w-14 h-14 bg-pink-50 dark:bg-pink-900/30 rounded-2xl flex items-center justify-center text-pink-600 dark:text-pink-400 mb-6 text-2xl group-hover:scale-110 transition-transform duration-300">
                                <FaClipboardList />
                            </div>
                            <h3 className="text-xl font-bold text-slate-900 dark:text-white mb-3">Quizzes</h3>
                            <p className="text-slate-500 dark:text-slate-400 leading-relaxed font-medium">
                                Take curated quizzes to validate fundamentals and technical readiness.
                            </p>
                        </div>
                    </div>

                    <div className="mt-20 text-center">
                        <Link to={user ? "/resume-booster" : "/register"} className="inline-flex items-center gap-3 px-10 py-5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-lg font-bold rounded-2xl shadow-xl shadow-blue-500/25 hover:shadow-blue-500/40 transform hover:-translate-y-1 active:scale-95 transition-all duration-300">
                            Get Started Now
                            <FaArrowRight />
                        </Link>
                    </div>
                </div>
            </main>

            {/* Footer Section - Matches Image Design */}
            <footer className="bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 pt-16 pb-8">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                        {/* Brand Column */}
                        <div className="lg:col-span-2">
                            <div className="flex items-center gap-2 mb-6">
                                <div className="p-2 bg-indigo-600 rounded-lg">
                                    <svg className="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <span className="text-2xl font-bold text-slate-900 dark:text-white">AI Interview Prep</span>
                            </div>
                            <p className="text-slate-500 dark:text-slate-400 leading-relaxed mb-8 max-w-sm">
                                Empowering your career journey with AI-driven insights, personalized preparation, and smart practice tools. Land your dream job with confidence.
                            </p>
                            <div className="flex gap-4">
                                <a href="#" className="w-10 h-10 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <FaTwitter size={20} />
                                </a>
                                <a href="#" className="w-10 h-10 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <FaLinkedin size={20} />
                                </a>
                                <a href="#" className="w-10 h-10 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <FaGithub size={20} />
                                </a>
                            </div>
                        </div>

                        {/* Quick Links */}
                        <div>
                            <h3 className="font-bold text-slate-900 dark:text-white text-lg mb-6">Quick Links</h3>
                            <ul className="space-y-4">
                                <li><Link to="/sessions" className="text-slate-500 hover:text-indigo-600 transition-colors">Sessions</Link></li>
                                <li><Link to="/prepare" className="text-slate-500 hover:text-indigo-600 transition-colors">Practice</Link></li>
                                <li><Link to="/codelab" className="text-slate-500 hover:text-indigo-600 transition-colors">codelab</Link></li>
                                <li><Link to="/quizzes" className="text-slate-500 hover:text-indigo-600 transition-colors">Quizzes</Link></li>
                            </ul>
                        </div>

                        {/* Contact & Support */}
                        <div>
                            <h3 className="font-bold text-slate-900 dark:text-white text-lg mb-6">Contact & Support</h3>
                            <ul className="space-y-4">
                                <li>
                                    <a href="mailto:support@ai.com" className="flex items-center gap-3 text-slate-500 hover:text-indigo-600 transition-colors">
                                        <FaEnvelope />
                                        <span>support@ai interviewprep.com</span>
                                    </a>
                                </li>
                                <li>
                                    <button onClick={() => setFeedbackOpen(true)} className="flex items-center gap-3 text-slate-500 hover:text-indigo-600 transition-colors">
                                        <FaComment />
                                        <span>Share Feedback</span>
                                    </button>
                                </li>
                                <li><a href="#" className="text-slate-500 hover:text-indigo-600 transition-colors">Help Center</a></li>
                                <li><a href="#" className="text-slate-500 hover:text-indigo-600 transition-colors">Privacy Policy</a></li>
                                <li><a href="#" className="text-slate-500 hover:text-indigo-600 transition-colors">Terms of Service</a></li>
                            </ul>
                        </div>
                    </div>

                    <div className="border-t border-slate-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-400">
                        <p>© 2026 AI Interview Prep. All rights reserved.</p>
                        <p>Built with AI to accelerate your career growth.</p>
                    </div>
                </div>
            </footer>
        </div>
    );
};

export default Dashboard;
