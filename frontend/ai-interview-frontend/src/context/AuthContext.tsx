import React, { createContext, useContext, useState, useEffect, type ReactNode } from 'react';

interface User {
    id: number;
    full_name: string;
    email: string;
}

interface AuthContextType {
    user: User | null;
    login: (user: User) => void;
    logout: () => void;
    isAuthenticated: boolean;
    isLoading: boolean;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider: React.FC<{ children: ReactNode }> = ({ children }) => {
    const [user, setUser] = useState<User | null>(null);
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
        const checkAuth = async () => {
            try {
                // CHANGED: Pointing to localhost:8000 where backend server is running
                const response = await fetch('http://localhost:8000/me.php', {
                    method: 'GET',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'include', // Important for cookies
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.authenticated) {
                        setUser(data.user);
                    }
                }
            } catch (error) {
                console.error("Failed to check auth status", error);
            } finally {
                setIsLoading(false);
            }
        };

        checkAuth();
    }, []);

    const login = (userData: User) => {
        setUser(userData);
    };

    const logout = async () => {
        try {
            await fetch('http://localhost:8000/logout.php', {
                method: 'GET',
                credentials: 'include',
            });
            setUser(null);
        } catch (error) {
            console.error("Logout failed", error);
            // Even if backend fails, clear frontend state
            setUser(null);
        }
    };

    return (
        <AuthContext.Provider value={{ user, login, logout, isAuthenticated: !!user, isLoading }}>
            {children}
        </AuthContext.Provider>
    );
};

export const useAuth = () => {
    const context = useContext(AuthContext);
    if (!context) {
        throw new Error('useAuth must be used within an AuthProvider');
    }
    return context;
};
