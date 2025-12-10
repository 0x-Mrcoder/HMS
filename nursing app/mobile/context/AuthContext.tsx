import React, { createContext, useContext, useState, useEffect } from 'react';
import * as SecureStore from 'expo-secure-store';
import { useRouter, useSegments } from 'expo-router';
import api from '../services/api';

type AuthContextType = {
    user: any;
    isLoading: boolean;
    signIn: (email: string, pass: string) => Promise<void>;
    signOut: () => Promise<void>;
};

const AuthContext = createContext<AuthContextType>({} as AuthContextType);

export const useAuth = () => useContext(AuthContext);

export const AuthProvider = ({ children }: { children: React.ReactNode }) => {
    const [user, setUser] = useState<any>(null);
    const [isLoading, setIsLoading] = useState(true);
    const router = useRouter();
    const segments = useSegments();

    useEffect(() => {
        const checkUser = async () => {
            try {
                const token = await SecureStore.getItemAsync('token');
                const userData = await SecureStore.getItemAsync('user');

                if (token && userData) {
                    setUser(JSON.parse(userData));
                }
            } catch (e) {
                console.error(e);
            } finally {
                setIsLoading(false);
            }
        };
        checkUser();
    }, []);

    useEffect(() => {
        if (isLoading) return;

        const inAuthGroup = segments[0] === '(auth)';

        if (!user && !inAuthGroup) {
            // Redirect to login
            router.replace('/');
        } else if (user && segments[0] === 'index') {
            // Redirect to dashboard
            router.replace('/dashboard');
        }
    }, [user, segments, isLoading]);

    const signIn = async (email: string, pass: string) => {
        try {
            const response = await api.post('/login', { email, password: pass });
            const { token, user } = response.data;

            await SecureStore.setItemAsync('token', token);
            await SecureStore.setItemAsync('user', JSON.stringify(user));

            setUser(user);
            router.replace('/dashboard');
        } catch (error) {
            console.error(error);
            throw error;
        }
    };

    const signOut = async () => {
        await SecureStore.deleteItemAsync('token');
        await SecureStore.deleteItemAsync('user');
        setUser(null);
        router.replace('/');
    };

    return (
        <AuthContext.Provider value={{ user, isLoading, signIn, signOut }}>
            {children}
        </AuthContext.Provider>
    );
};
