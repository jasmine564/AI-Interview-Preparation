# AI Interview Project

Welcome to the **AI Interview Project**! This project is a comprehensive toolkit designed to help users prepare for technical interviews. It features AI-driven resume analysis, dynamic mock interview question generation, insightful candidate evaluation, and a built-in code execution environment to practice algorithmic problems.

## 🌟 Features

- **AI-Powered Mock Interviews**: Generates role-specific, scenario-based interview questions using the OpenRouter AI (`gpt-4o-mini` model). Evaluates user responses and provides deep-dive explanations and constructive feedback.
- **Smart Resume Analysis**: Uses AI to parse and score user resumes against job descriptions, providing optimization suggestions, ATS compatibility checks, and missing keywords identification.
- **Interactive Coding Practice**: 
  - Integrated **Monaco Editor** in the frontend for an authentic IDE experience.
  - Safe, backend code execution supporting both interpreted languages (Python, JavaScript via local process) and compiled languages (C, C++, Java via Docker container `code-runner`).
- **User Progression**: Tracks user progress such as solved problems, pinned/saved roles, and past feedbacks using a MySQL database layout.
- **Modern Full-Stack Architecture**: Built with a snappy, modern stack ensuring a fast, responsive user interface.

---

## 🏗️ Architecture & Tech Stack

### Frontend
Situated in the `frontend/ai-interview-frontend/` directory, the frontend is built entirely using:
- **Framework**: React v19
- **Build Tool**: Vite
- **Language**: TypeScript
- **Styling**: Tailwind CSS & Autoprefixer
- **Key Libraries**: `@monaco-editor/react` (code editing), `react-router-dom` (routing), `react-icons` (UI elements).

### Backend
Situated in the `backend/` directory, functioning as a robust API layer mapping to the frontend:
- **Environment**: Core PHP (served typically via XAMPP)
- **Database**: MySQL over PDO extension.
- **AI Integration**: OpenRouter API logic handled tightly in `ai_service.php` with robust error fallbacks.
- **Execution Engine**: Custom implementation of sub-process creation (for JS/Python) and Docker integration (`run_code.php`) for execution isolation.

---

## 🚀 Getting Started

Follow these steps to set up the project locally.

### Prerequisites
- [XAMPP](https://www.apachefriends.org/index.html) or equivalent PHP/MySQL local environment.
- [Node.js](https://nodejs.org/) (v18+ recommended) and npm.
- [Git](https://git-scm.com/).
- [Docker](https://www.docker.com/) (Required for running C, C++, and Java code execution).
- **OpenRouter API Key** (for AI features to function).

### 1. Backend & Database Setup
1. Ensure your MySQL server (via XAMPP) is running.
2. The project connects to the database: `ai_interview_db`. You can execute the setup scripts found in the `backend` directory (like `setup_db.php`, `setup_practice_db.php`, `setup_quiz_db.php`) sequentially directly from your browser (`http://localhost/ai-interview-project/backend/setup_db.php`).
3. Set your API Keys. In the `backend/` folder, create and modify `.env`:
   ```env
   AI_API_KEY=your_openrouter_api_key_here
   ```

### 2. Frontend Setup
1. Open a terminal and navigate to the frontend directory:
   ```bash
   cd frontend/ai-interview-frontend
   ```
2. Install the necessary Node.js modules:
   ```bash
   npm install
   ```

### 3. Docker Setup (Code Execution)
If you want to test compiled languages out-of-the-box, build your code-execution docker image first. While the Dockerfile lives in the backend, you can typically run an alpine or gcc-linked image:
```bash
# Example tag logic expected by the backend script
docker build -t code-runner ./backend
```

### 4. Running the Application
The frontend features a `concurrently` script that allows you to start both the PHP backend development server and the Vite dev server at once.
In the frontend directory:
```bash
npm run dev
```
- **Backend**: Runs quietly on `http://localhost:8000`.
- **Frontend**: Accessible usually at `http://localhost:5173`. Make sure to navigate to the link provided in the terminal.

---

## 📁 Directory Structure Overview

```text
ai-interview-project/
├── backend/                  # PHP API Layer
│   ├── .env                  # Environment Variables (AI API keys)
│   ├── ai_service.php        # Core AI integration class
│   ├── db.php                # PDO Database connection template
│   ├── run_code.php          # Code execution router (Docker & Local)
│   ├── ResumeParser.php      # Base for handling resume processing endpoints
│   ├── login.php / register.php # User Authentication
│   ├── uploads/              # Transient storage for user resumes
│   └── setup_*.php           # Essential database seeding/migration scripts
└── frontend/                 # UI Layer
    └── ai-interview-frontend/ 
        ├── src/              # React Components, Hooks, Context, Pages
        ├── public/           # Static assets
        ├── package.json      # Node.js dependencies and run scripts
        ├── tailwind.config.js# Tailwind style tokens
        └── vite.config.ts    # Vite bundler configurations
```

## 📜 Documentation Generation
This README contains the architectural and technical breakdown of the `ai-interview-project`. You can use the findings outlined here as a base template for generating a formal user manual or robust developer documentation spanning component definitions, endpoint documentation, and deployment strategies.
