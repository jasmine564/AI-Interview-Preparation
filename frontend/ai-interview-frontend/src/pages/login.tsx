import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { FaEye, FaEyeSlash, FaLock, FaUserGraduate } from 'react-icons/fa';

const Login: React.FC = () => {
    const navigate = useNavigate();
    const { login } = useAuth();
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [showPassword, setShowPassword] = useState(false);

    // Clear form fields on component mount
    useEffect(() => {
        setEmail('');
        setPassword('');
    }, []);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        try {
            const response = await fetch('http://localhost:8000/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password }),
                credentials: 'include'
            });

            const data = await response.json();

            if (response.ok) {
                login(data.user);
                navigate('/');
            } else {
                alert(data.message || 'Login failed');
            }
        } catch (error: any) {
            console.error('Login error:', error);
            alert(error.message || 'An error occurred during login');
        }
    };

    return (
        <div className="min-h-screen flex flex-col md:flex-row font-sans">
            {/* Left Section - Marketing */}
            <div className="w-full md:w-1/2 bg-slate-50 relative overflow-hidden flex flex-col justify-center px-8 md:px-16 lg:px-24">
                {/* Background decorations */}
                <div className="absolute top-0 left-0 w-full h-full">
                    <div className="absolute top-[-10%] right-[-5%] w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-blob"></div>
                    <div className="absolute bottom-[-10%] left-[-10%] w-72 h-72 bg-indigo-200 rounded-full mix-blend-multiply filter blur-2xl opacity-30 animate-blob animation-delay-2000"></div>
                </div>

                <div className="relative z-10 space-y-8">
                    <div>
                        <h1 className="text-4xl lg:text-5xl font-extrabold text-slate-800 tracking-tight leading-tight">
                            Welcome Back to <br />
                            <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                                Your Success
                            </span>
                        </h1>
                        <div className="h-1.5 w-24 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full mt-4"></div>
                    </div>

                    <div className="space-y-6 text-slate-600">
                        <div className="flex items-center space-x-4 group">
                            <div className="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                <FaLock className="w-6 h-6" />
                            </div>
                            <div>
                                <h3 className="font-bold text-slate-800">Secure Login</h3>
                                <p className="text-sm">Bank-grade encryption for your data</p>
                            </div>
                        </div>

                        <div className="flex items-center space-x-4 group">
                            <div className="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                <FaUserGraduate className="w-6 h-6" />
                            </div>
                            <div>
                                <h3 className="font-bold text-slate-800">Continue Practice</h3>
                                <p className="text-sm">Pick up right where you left off</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Right Section - Login Form */}
            <div className="w-full md:w-1/2 bg-white flex items-center justify-center p-4 sm:p-8">
                <div className="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 border border-slate-100">
                    <div className="text-center mb-8">
                        <h2 className="text-2xl font-bold text-slate-800">Sign in to your account</h2>
                        <p className="text-slate-500 mt-2 text-sm">Enter your details to access your dashboard</p>
                    </div>

                    <form className="space-y-6" onSubmit={handleSubmit} autoComplete="off">
                        <div>
                            <label htmlFor="email" className="block text-sm font-semibold text-slate-700 mb-2">
                                Email Address
                            </label>
                            <input
                                id="email"
                                type="email"
                                required
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                className="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-slate-700 placeholder-slate-400"
                                placeholder="name@company.com"
                                autoComplete="new-password"
                            />
                        </div>

                        <div>
                            <div className="flex justify-between items-center mb-2">
                                <label htmlFor="password" className="block text-sm font-semibold text-slate-700">
                                    Password
                                </label>
                                <Link to="/forgot-password" className="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                    Forgot password?
                                </Link>
                            </div>
                            <div className="relative">
                                <input
                                    id="password"
                                    type={showPassword ? "text" : "password"}
                                    required
                                    value={password}
                                    onChange={(e) => setPassword(e.target.value)}
                                    className="w-full px-4 py-3 rounded-lg border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none text-slate-700 placeholder-slate-400"
                                    placeholder="Enter your password"
                                    autoComplete="new-password" // Prevents most browsers from autofilling
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

                        <button
                            type="submit"
                            className="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transform transition-all hover:shadow-lg focus:ring-4 focus:ring-blue-300 focus:outline-none active:scale-95"
                        >
                            Sign in
                        </button>
                    </form>



                    <p className="mt-8 text-center text-sm text-slate-600">
                        Don't have an account?{' '}
                        <Link to="/register" className="font-bold text-blue-600 hover:text-blue-700 hover:underline">
                            Register here
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    );
};

export default Login;
