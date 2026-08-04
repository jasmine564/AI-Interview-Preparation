import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import Login from './pages/login';
import Codelab from './pages/Codelab';
import Register from './pages/register';
import ForgotPassword from './pages/forgot-password';
import Dashboard from './pages/dashboard';
import RolesPage from './pages/RolesPage';
import QuestionsPage from './pages/QuestionsPage';
import PracticeEditor from './pages/PracticeEditor';
import ResumeBooster from './pages/ResumeBooster';
import PrepareInterview from './pages/PrepareInterview';
import Quizzes from './pages/Quizzes';
import QuizPlayer from './pages/QuizPlayer';

function App() {
  return (
    <Router>
      <AuthProvider>
        <Routes>
          {/* Route for Login Page */}
          <Route path="/login" element={<Login />} />

          {/* Route for Register Page */}
          <Route path="/register" element={<Register />} />

          {/* Route for Forgot Password Page */}
          <Route path="/forgot-password" element={<ForgotPassword />} />

          {/* Route for Practise Page */}
          <Route path="/codelab" element={<Codelab />} />
          <Route path="/practice/:id" element={<PracticeEditor />} />

          {/* Default Route (Home) */}
          <Route path="/" element={<Dashboard />} />

          {/* New Roles & Sessions Routes */}
          <Route path="/sessions" element={<RolesPage />} />
          <Route path="/session/:roleId" element={<QuestionsPage />} />

          {/* Resume Booster */}
          <Route path="/resume-booster" element={<ResumeBooster />} />

          {/* New Prepare Page */}
          <Route path="/prepare" element={<PrepareInterview />} />

          {/* Quizzes */}
          <Route path="/quizzes" element={<Quizzes />} />
          <Route path="/quizzes/:id" element={<QuizPlayer />} />
        </Routes>
      </AuthProvider>
    </Router>
  );
}

export default App;
