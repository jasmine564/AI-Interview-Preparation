import React from 'react';
import { useTheme } from '../context/ThemeContext';
import { FaSun, FaMoon } from 'react-icons/fa';

const ThemeToggle: React.FC = () => {
    const { theme, toggleTheme } = useTheme();

    return (
        <button
            onClick={toggleTheme}
            className="p-2 rounded-lg bg-secondary text-secondary-foreground hover:bg-accent hover:text-accent-foreground transition-colors"
            aria-label="Toggle theme"
        >
            {theme === 'light' ? <FaMoon size={20} /> : <FaSun size={20} />}
        </button>
    );
};

export default ThemeToggle;
