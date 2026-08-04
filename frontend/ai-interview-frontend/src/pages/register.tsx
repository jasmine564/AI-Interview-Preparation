import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { FaEye, FaEyeSlash, FaRocket, FaCheck } from 'react-icons/fa';

const Register: React.FC = () => {
    const navigate = useNavigate();
    const { login } = useAuth();

    // State management for form fields
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        password: '',
        confirmPassword: ''
    });
    const [showPassword, setShowPassword] = useState(false);

    // Clear form fields on component mount
    useEffect(() => {
        setFormData({
            name: '',
            email: '',
            password: '',
            confirmPassword: ''
        });
    }, []);

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value
        });
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        try {
            const response = await fetch('http://localhost:8000/register.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    full_name: formData.name,
                    email: formData.email,
                    password: formData.password
                }),
                credentials: 'include'
            });

            const data = await response.json();

            if (response.ok) {
                // Auto-login
                login(data.user);
                navigate('/');
            } else {
                alert(data.message || 'Registration failed');
            }
        } catch (error) {
            console.error('Registration error:', error);
            alert('An error occurred during registration');
        }
    };

    return (
        <div className="min-h-screen flex flex-col md:flex-row font-sans">
            {/* Left Section - Marketing/Offer */}
            <div className="w-full md:w-1/2 bg-slate-50 relative overflow-hidden flex flex-col justify-center px-8 md:px-16 lg:px-24 py-12 md:py-0">
                {/* Background decorations */}
                <div className="absolute top-0 left-0 w-full h-full">
                    <div className="absolute top-[10%] left-[10%] w-64 h-64 bg-blue-200 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-blob"></div>
                    <div className="absolute bottom-[10%] right-[10%] w-64 h-64 bg-indigo-200 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-blob animation-delay-2000"></div>
                </div>

                <div className="relative z-10 space-y-8">
                    <div>
                        <span className="inline-block py-1 px-3 rounded-full bg-blue-100 text-blue-600 text-xs font-bold tracking-wider uppercase mb-4">
                            Limited Time Offer
                        </span>
                        <h1 className="text-4xl lg:text-5xl font-extrabold text-slate-800 tracking-tight leading-tight">
                            Join Thousands of <br />
                            <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                                Successful Candidates
                            </span>
                        </h1>
                        <div className="h-1.5 w-24 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full mt-4"></div>
                    </div>

                    <div className="p-6 bg-white bg-opacity-60 backdrop-blur-sm rounded-2xl border border-white/50 shadow-sm">
                        <div className="flex items-center space-x-3 mb-2">
                            <FaRocket className="text-blue-600 text-xl" />
                            <h3 className="font-bold text-slate-800 text-lg">Sign Up Now:</h3>
                        </div>
                        <p className="text-slate-600 font-medium">Claim Your 1 FREE AI Interview</p>
                    </div>

                    <div className="space-y-4 text-slate-600">
                        <div className="flex items-center space-x-3">
                            <div className="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                <FaCheck size={12} />
                            </div>
                            <span className="font-medium">100% Free Trial</span>
                        </div>
                        <div className="flex items-center space-x-3">
                            <div className="flex-shrink-0 w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                <FaCheck size={12} />
                            </div>
                            <span className="font-medium">No Credit Card Required</span>
                        </div>
                    </div>
                </div>
            </div>

            {/* Right Section - Register Form */}
            <div className="w-full md:w-1/2 bg-white flex items-center justify-center p-4 sm:p-8">
                <div className="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 border border-slate-100">
                    <div className="text-center mb-8">
                        <h2 className="text-2xl font-bold text-slate-800">Create your account</h2>
                        <p className="text-slate-500 mt-2 text-sm">Start your journey to interview mastery today</p>
                    </div>

                    <form className="space-y-5" onSubmit={handleSubmit} autoComplete="off">
                        <div>
                            <label htmlFor="name" className="block text-sm font-semibold text-slate-700 mb-1">
                                Full Name
                            </label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                required
                                value={formData.name}
                                onChange={handleChange}
                                className="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-slate-700 placeholder-slate-400"
                                placeholder="Enter your full name"
                                autoComplete="new-password"
                            />
                        </div>

                        <div>
                            <label htmlFor="email" className="block text-sm font-semibold text-slate-700 mb-1">
                                Email Address
                            </label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                required
                                value={formData.email}
                                onChange={handleChange}
                                className="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-slate-700 placeholder-slate-400"
                                placeholder="name@company.com"
                                autoComplete="new-password"
                            />
                        </div>

                        <div>
                            <label htmlFor="password" className="block text-sm font-semibold text-slate-700 mb-1">
                                Password
                            </label>
                            <div className="relative">
                                <input
                                    id="password"
                                    name="password"
                                    type={showPassword ? "text" : "password"}
                                    required
                                    value={formData.password}
                                    onChange={handleChange}
                                    className="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-slate-700 placeholder-slate-400"
                                    placeholder="Create a password"
                                    autoComplete="new-password"
                                />
                                <button
                                    type="button"
                                    onClick={() => setShowPassword(!showPassword)}
                                    className="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors"
                                >
                                    {showPassword ? <FaEyeSlash size={20} /> : <FaEye size={20} />}
                                </button>
                            </div>
                        </div>

                        {/* <div>
                            <label htmlFor="confirmPassword" className="block text-sm font-semibold text-slate-700 mb-1">
                                Confirm Password
                            </label>
                            <div className="relative">
                                <input
                                    id="confirmPassword"
                                    name="confirmPassword"
                                    type={showConfirmPassword ? "text" : "password"}
                                    required
                                    value={formData.confirmPassword}
                                    onChange={handleChange}
                                    className="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-slate-700 placeholder-slate-400"
                                    placeholder="Confirm your password"
                                />
                                <button
                                    type="button"
                                    onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                                    className="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors"
                                >
                                    {showConfirmPassword ? <FaEyeSlash size={20} /> : <FaEye size={20} />}
                                </button>
                            </div>
                        </div> */}

                        <button
                            type="submit"
                            className="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transform transition-all hover:shadow-lg focus:ring-4 focus:ring-blue-300 focus:outline-none active:scale-95 mt-2"
                        >
                            Get Started
                        </button>
                    </form>



                    <p className="mt-8 text-center text-sm text-slate-600">
                        Already have an account?{' '}
                        <Link to="/login" className="font-bold text-blue-600 hover:text-blue-700 hover:underline">
                            Login here
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    );
};

export default Register;
