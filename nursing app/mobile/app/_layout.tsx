import { DarkTheme, DefaultTheme, ThemeProvider } from '@react-navigation/native';
import { useFonts } from 'expo-font';
import { Stack } from 'expo-router';
import * as SplashScreen from 'expo-splash-screen';
import { useEffect } from 'react';
import { MD3LightTheme as PaperDefaultTheme, PaperProvider, configureFonts } from 'react-native-paper';
import { AuthProvider } from '../context/AuthContext';
import { Colors } from '../constants/Colors';
import { SafeAreaProvider } from 'react-native-safe-area-context';
import {
    InstrumentSans_400Regular,
    InstrumentSans_500Medium,
    InstrumentSans_600SemiBold,
    InstrumentSans_700Bold,
} from '@expo-google-fonts/instrument-sans';

export {
    // Catch any errors thrown by the Layout component.
    ErrorBoundary,
} from 'expo-router';

// Prevent the splash screen from auto-hiding before asset loading is complete.
SplashScreen.preventAutoHideAsync();

const fontConfig = {
    fontFamily: 'InstrumentSans_400Regular',
};

const theme = {
    ...PaperDefaultTheme,
    fonts: configureFonts({ config: fontConfig }),
    colors: {
        ...PaperDefaultTheme.colors,
        primary: Colors.primary,
        secondary: Colors.secondary,
        background: Colors.background,
        surface: Colors.surface,
        error: Colors.error,
        onPrimary: '#FFFFFF',
        onSurface: Colors.text,
    },
};

export default function RootLayout() {
    const [loaded, error] = useFonts({
        InstrumentSans_400Regular,
        InstrumentSans_500Medium,
        InstrumentSans_600SemiBold,
        InstrumentSans_700Bold,
    });

    useEffect(() => {
        if (error) throw error;
    }, [error]);

    useEffect(() => {
        if (loaded) {
            SplashScreen.hideAsync();
        }
    }, [loaded]);

    if (!loaded) {
        return null;
    }

    return (
        <SafeAreaProvider>
            <PaperProvider theme={theme}>
                <AuthProvider>
                    <Stack screenOptions={{ headerShown: false }}>
                        <Stack.Screen name="index" />
                        <Stack.Screen name="dashboard" />
                        <Stack.Screen name="medications/[id]" options={{ title: 'Medications', headerShown: true }} />
                        <Stack.Screen name="vitals/[id]" options={{ title: 'Vitals', headerShown: true }} />
                        <Stack.Screen name="notes/[id]" options={{ title: 'Notes', headerShown: true }} />
                    </Stack>
                </AuthProvider>
            </PaperProvider>
        </SafeAreaProvider>
    );
}
