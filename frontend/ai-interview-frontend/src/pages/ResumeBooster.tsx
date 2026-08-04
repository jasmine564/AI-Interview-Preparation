import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const ResumeBooster: React.FC = () => {
    const navigate = useNavigate();
    const { user, logout } = useAuth();
    const [step, setStep] = useState<1 | 2>(1);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const [results, setResults] = useState<any>(null);
    const [jobDescription, setJobDescription] = useState("");
    const fileInputRef = React.useRef<HTMLInputElement>(null);

    const handleUploadClick = () => {
        fileInputRef.current?.click();
    };

    const handleFileChange = async (event: React.ChangeEvent<HTMLInputElement>) => {
        const file = event.target.files?.[0];
        if (!file) return;

        const allowedTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword'];
        if (!allowedTypes.includes(file.type)) {
            setError("Invalid file type. Please upload a PDF or DOCX.");
            return;
        }

        setLoading(true);
        setError(null);

        const formData = new FormData();
        formData.append('resume_file', file);
        if (jobDescription) {
            formData.append('job_description', jobDescription);
        }

        try {
            const response = await fetch('http://localhost/ai-interview-project/backend/upload_resume.php', {
                method: 'POST',
                body: formData,
            });

            const data = await response.json();

            if (data.success && data.data) {
                setResults(data.data);
                setStep(2);
            } else {
                setError(data.error || "Analysis failed. Please try again.");
            }
        } catch (err) {
            setError("Network error. Please try again.");
        } finally {
            setLoading(false);
        }
    };

    const handleDownload = async () => {
        if (!results?.optimizedResumeText) return;

        try {
            const response = await fetch('http://localhost/ai-interview-project/backend/download_resume.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    content: results.optimizedResumeText,
                    format: 'txt'
                })
            });

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = "Optimized_Resume.txt";
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        } catch (e) {
            alert("Download failed");
        }
    };

    return (
        <div className="min-h-screen bg-white font-sans text-gray-900 selection:bg-blue-100 pb-20">
            {/* Navbar (Exact Copy from Dashboard) */}
            <nav className="fixed w-full z-50 bg-white shadow-sm border-b border-gray-100 transition-all duration-300">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-20 items-center">
                        <div className="flex-shrink-0 flex items-center">
                            {/* Logo Icon */}
                            <svg className="h-8 w-8 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            <span className="font-bold text-xl text-gray-900 tracking-tight">AI Interview Prep</span>
                        </div>
                        <div className="hidden md:flex space-x-8">
                            <Link to="/" className="text-gray-600 hover:text-blue-600 transition font-medium text-sm lg:text-base">Home</Link>
                            <a href="#" className="text-gray-600 hover:text-blue-600 transition font-medium text-sm lg:text-base">Practice</a>
                            <Link to="/codelab" className="text-gray-600 hover:text-blue-600 transition font-medium text-sm lg:text-base">Codelab</Link>
                            <Link to="/sessions" className="text-gray-600 hover:text-blue-600 transition font-medium text-sm lg:text-base">Sessions</Link>
                        </div>

                        <div className="flex items-center space-x-4">
                            {user ? (
                                <div className="flex items-center space-x-4">
                                    <span className="text-gray-900 font-medium">Hi, {user.full_name}</span>
                                    <button
                                        onClick={async () => {
                                            await logout();
                                            navigate('/login');
                                        }}
                                        className="text-gray-700 hover:text-red-600 font-medium px-3 py-2 rounded-md transition text-sm lg:text-base"
                                    >
                                        Logout
                                    </button>
                                </div>
                            ) : (
                                <>
                                    <Link to="/login" className="text-gray-900 hover:text-blue-600 font-medium px-4 py-2 rounded-lg transition text-sm lg:text-base">Login</Link>
                                    <Link to="/register" className="bg-blue-600 text-white px-6 py-2.5 rounded-full font-medium hover:bg-blue-700 transition shadow-lg hover:shadow-blue-500/30 transform hover:-translate-y-0.5 text-sm lg:text-base">Get Started</Link>
                                </>
                            )}
                        </div>
                    </div>
                </div>
            </nav>

            <main className="pt-32 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                {/* Header */}
                <div className="text-center mb-12">
                    <h1 className="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 tracking-tight mb-8">
                        Boost Your Resume in Minutes with <span className="text-blue-600">AI</span>.
                    </h1>

                    {/* Steps Indicator */}
                    <div className="flex justify-center items-center gap-4 sm:gap-12 text-sm font-medium text-gray-500 overflow-x-auto pb-4">
                        <div className={`flex flex-col items-center gap-2 min-w-[100px] ${step === 1 ? 'text-blue-600' : ''}`}>
                            <div className={`w-10 h-10 rounded-full flex items-center justify-center ${step === 1 ? 'bg-blue-100' : 'bg-gray-100'}`}>
                                <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>
                            <span>Upload resume</span>
                        </div>
                        <div className="hidden sm:block h-px w-12 bg-gray-200 border-t border-dashed border-gray-400"></div>

                        <div className={`flex flex-col items-center gap-2 min-w-[100px] ${step === 2 || loading ? 'text-blue-600' : ''}`}>
                            <div className={`w-10 h-10 rounded-full flex items-center justify-center ${step === 2 || loading ? 'bg-blue-100' : 'bg-gray-100'}`}>
                                <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <span>AI scans resume</span>
                        </div>
                        <div className="hidden sm:block h-px w-12 bg-gray-200 border-t border-dashed border-gray-400"></div>

                        <div className={`flex flex-col items-center gap-2 min-w-[100px] ${step === 2 ? 'text-blue-600' : ''}`}>
                            <div className={`w-10 h-10 rounded-full flex items-center justify-center ${step === 2 ? 'bg-blue-100' : 'bg-gray-100'}`}>
                                <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span>Results</span>
                        </div>
                    </div>
                </div>

                {loading ? (
                    <div className="max-w-xl mx-auto text-center py-20">
                        <div className="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto mb-6"></div>
                        <h3 className="text-xl font-bold text-gray-900 mb-2">Analyzing your resume...</h3>
                        <p className="text-gray-500">Checking ATS compatibility, keywords, and grammar.</p>
                    </div>
                ) : step === 1 ? (
                    /* Step 1: Input Layout */
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 animate-fade-in-up">
                        {/* Left Column: Upload */}
                        <div className="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                            <div className="border-2 border-dashed border-gray-300 rounded-xl p-10 flex flex-col items-center justify-center text-center hover:bg-gray-50 transition-colors h-full min-h-[300px]">
                                <div className="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-6">
                                    <svg className="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                </div>
                                <h3 className="text-xl font-bold text-gray-900 mb-2">Drop your resume here or choose a file</h3>
                                <p className="text-gray-500 mb-8">Only .docx and .pdf files are supported</p>

                                <input
                                    type="file"
                                    accept=".pdf,.docx,.doc"
                                    ref={fileInputRef}
                                    onChange={handleFileChange}
                                    className="hidden"
                                />

                                <button
                                    onClick={handleUploadClick}
                                    className="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-blue-600/30 transition-all transform hover:-translate-y-0.5 flex items-center"
                                >
                                    <svg className="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    Upload Resume
                                </button>

                                {error && <p className="mt-4 text-red-600 font-medium">{error}</p>}

                                <div className="mt-6 flex items-center text-gray-400 text-sm">
                                    <svg className="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Safe and confidential
                                </div>
                            </div>
                        </div>

                        {/* Right Column: Job Description */}
                        <div className="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow flex flex-col">
                            <div className="mb-4">
                                <label className="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                    <svg className="w-4 h-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Job Description (Optional)
                                </label>
                                <select className="w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md border">
                                    <option>Paste Job Description</option>
                                    <option>Enter URL (Coming Soon)</option>
                                </select>
                            </div>
                            <textarea
                                className="flex-1 w-full border border-gray-300 rounded-xl p-4 text-gray-600 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all resize-none bg-gray-50"
                                placeholder="Paste job description or vacancy details here for better keyword matching..."
                                value={jobDescription}
                                onChange={(e) => setJobDescription(e.target.value)}
                            ></textarea>
                            <p className="mt-3 text-xs text-gray-400 text-right">{jobDescription.length}/5000 characters</p>
                        </div>
                    </div>
                ) : (
                    /* Step 2: Analysis Results (Real Data) */
                    <div className="animate-fade-in-up">
                        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            {/* Score Card */}
                            <div className="col-span-1 lg:col-span-1 bg-white p-6 rounded-2xl border border-gray-100 shadow-lg">
                                <h3 className="text-lg font-bold text-gray-900 mb-6">Resume Score</h3>
                                <div className="flex justify-center mb-6">
                                    <div className="relative w-40 h-40">
                                        <svg className="w-full h-full transform -rotate-90">
                                            <circle cx="80" cy="80" r="70" stroke="#f3f4f6" strokeWidth="10" fill="transparent" />
                                            <circle cx="80" cy="80" r="70" stroke={results.resumeScore > 70 ? "#2563eb" : (results.resumeScore > 50 ? "#eab308" : "#ef4444")} strokeWidth="10" fill="transparent" strokeDasharray="440" strokeDashoffset={440 - (440 * results.resumeScore / 100)} strokeLinecap="round" />
                                        </svg>
                                        <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
                                            <span className="text-4xl font-extrabold text-gray-900">{results.resumeScore}%</span>
                                            <p className="text-xs text-gray-500 font-medium uppercase tracking-wide mt-1">
                                                {results.resumeScore > 80 ? "Excellent" : (results.resumeScore > 60 ? "Good" : "Needs Work")}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div className="space-y-4">
                                    <div className="flex justify-between items-center text-sm">
                                        <span className="text-gray-600">ATS Compatibility</span>
                                        <span className={`font-bold ${results.atsCompatibility === 'High' ? 'text-green-600' : 'text-yellow-600'}`}>{results.atsCompatibility}</span>
                                    </div>
                                    <div className="w-full bg-gray-100 rounded-full h-2">
                                        <div className={`h-2 rounded-full ${results.atsCompatibility === 'High' ? 'bg-green-500' : 'bg-yellow-500'}`} style={{ width: results.atsCompatibility === 'High' ? '90%' : '60%' }}></div>
                                    </div>
                                    <div className="flex justify-between items-center text-sm">
                                        <span className="text-gray-600">Keyword Match</span>
                                        <span className="font-bold text-yellow-600">{results.keywordMatch}</span>
                                    </div>
                                    <div className="w-full bg-gray-100 rounded-full h-2">
                                        <div className="bg-yellow-500 h-2 rounded-full" style={{ width: results.keywordMatch === 'High' ? '90%' : (results.keywordMatch === 'Medium' ? '60%' : '30%') }}></div>
                                    </div>
                                </div>
                            </div>

                            {/* Detailed Analysis */}
                            <div className="col-span-1 lg:col-span-2 bg-white p-8 rounded-2xl border border-gray-100 shadow-lg">
                                <div className="flex justify-between items-start mb-6">
                                    <div>
                                        <h3 className="text-2xl font-bold text-gray-900">Analysis Report</h3>
                                        <p className="text-gray-500">Based on industry standards</p>
                                    </div>
                                    <span className={`inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${results.atsFriendly ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}>
                                        ATS Friendly: {results.atsFriendly ? 'Yes' : 'Needs Work'}
                                    </span>
                                </div>

                                <div className="space-y-6">
                                    {results.missingKeywords && results.missingKeywords.length > 0 && (
                                        <div className="flex items-start p-4 bg-red-50 rounded-xl border border-red-100">
                                            <div className="flex-shrink-0">
                                                <svg className="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            </div>
                                            <div className="ml-4">
                                                <h4 className="text-base font-bold text-gray-900">Missing Critical Keywords</h4>
                                                <p className="text-sm text-gray-600 mt-1">Your resume is missing keywords: {results.missingKeywords.join(", ")}.</p>
                                            </div>
                                        </div>
                                    )}

                                    {results.suggestions && results.suggestions.length > 0 && (
                                        <div className="flex items-start p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                                            <div className="flex-shrink-0">
                                                <svg className="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                            </div>
                                            <div className="ml-4">
                                                <h4 className="text-base font-bold text-gray-900">Suggestions</h4>
                                                <ul className="text-sm text-gray-600 mt-1 list-disc pl-4">
                                                    {results.suggestions.map((s: string, i: number) => <li key={i}>{s}</li>)}
                                                </ul>
                                            </div>
                                        </div>
                                    )}
                                </div>

                                <div className="mt-8 flex flex-col sm:flex-row gap-4">
                                    <button
                                        onClick={handleDownload}
                                        className="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition shadow-lg flex justify-center items-center"
                                    >
                                        <svg className="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        Download Optimized Resume
                                    </button>
                                    <Link
                                        to="/sessions"
                                        className="flex-1 bg-white border border-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 hover:border-blue-200 hover:text-blue-600 transition shadow-sm flex justify-center items-center"
                                    >
                                        View Job Roles
                                        <svg className="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </main>
        </div>
    );
};

export default ResumeBooster;
