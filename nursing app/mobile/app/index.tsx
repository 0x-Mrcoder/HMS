import { useState, useEffect } from 'react';
import { View, StyleSheet, Image, KeyboardAvoidingView, Platform, Dimensions, StatusBar } from 'react-native';
import { TextInput, Button, Text, Surface, useTheme, ActivityIndicator, Snackbar } from 'react-native-paper';
import { useAuth } from '../context/AuthContext';
import { useRouter } from 'expo-router';
import { SafeAreaView } from 'react-native-safe-area-context';
import api from '../services/api';

const { width, height } = Dimensions.get('window');

export default function LoginScreen() {
    const [email, setEmail] = useState('nurse@hms.com');
    const [password, setPassword] = useState('123456');
    const [loading, setLoading] = useState(false);
    const [fetchingSettings, setFetchingSettings] = useState(true);
    const [hospitalAuth, setHospitalAuth] = useState<any>(null);

    const { signIn } = useAuth();
    const theme = useTheme();

    useEffect(() => {
        fetchSettings();
    }, []);

    const fetchSettings = async () => {
        try {
            const response = await api.get('/settings');
            setHospitalAuth(response.data.hospital);
        } catch (error) {
            console.log('Error fetching settings:', error);
        } finally {
            // Add a small artificial delay to show the nice loading screen for at least 1.5s
            setTimeout(() => setFetchingSettings(false), 1500);
        }
    };

    // Snackbar State
    const [snackbarVisible, setSnackbarVisible] = useState(false);
    const [snackbarMessage, setSnackbarMessage] = useState('');

    const showSnackbar = (message: string) => {
        setSnackbarMessage(message);
        setSnackbarVisible(true);
    };

    const handleLogin = async () => {
        setLoading(true);
        try {
            await signIn(email, password);
        } catch (e: any) {
            showSnackbar(e.message || 'Login failed. Please check your credentials.');
        } finally {
            setLoading(false);
        }
    };

    const logoUrl = hospitalAuth?.logo_path
        ? api.defaults.baseURL?.replace('/api/nursing', '') + '/' + hospitalAuth.logo_path
        : null;

    // ... (Loading state remains same, start of Main UI)

    // 2. Premium Login UI
    return (
        <View style={styles.container}>
            <StatusBar barStyle="light-content" />

            {/* Green Top Section */}
            <View style={[styles.topSection, { backgroundColor: theme.colors.primary }]}>
                <View style={styles.headerContent}>
                    {logoUrl && (
                        <Image
                            source={{ uri: logoUrl }}
                            style={[styles.logo, { tintColor: 'white', width: 90, height: 90 }]}
                            resizeMode="contain"
                        />
                    )}
                    <Text variant="headlineMedium" style={styles.headerTitle}>
                        Nursing Portal
                    </Text>
                    <Text variant="bodyLarge" style={styles.headerSubtitle}>
                        Sign in to access your ward
                    </Text>
                </View>
            </View>

            {/* Overlapping Form Card */}
            <View style={styles.bottomSection}>
                <Surface style={styles.card} elevation={3}>
                    <Text variant="titleLarge" style={{ marginBottom: 24, fontWeight: 'bold', color: theme.colors.secondary, textAlign: 'center' }}>
                        Welcome Back
                    </Text>

                    <TextInput
                        label="Email Address"
                        value={email}
                        onChangeText={setEmail}
                        mode="outlined"
                        style={styles.input}
                        autoCapitalize="none"
                        left={<TextInput.Icon icon="email-outline" color={theme.colors.secondary} />}
                        outlineStyle={{ borderRadius: 12, borderColor: '#eee' }}
                        contentStyle={{ backgroundColor: '#fff' }}
                        theme={{ roundness: 12 }}
                    />

                    <TextInput
                        label="Password"
                        value={password}
                        onChangeText={setPassword}
                        mode="outlined"
                        secureTextEntry
                        style={styles.input}
                        left={<TextInput.Icon icon="lock-outline" color={theme.colors.secondary} />}
                        outlineStyle={{ borderRadius: 12, borderColor: '#eee' }}
                        contentStyle={{ backgroundColor: '#fff' }}
                        theme={{ roundness: 12 }}
                        right={<TextInput.Icon icon="eye" />}
                    />

                    <Button
                        mode="contained"
                        onPress={handleLogin}
                        loading={loading}
                        style={[styles.button, { backgroundColor: theme.colors.primary }]}
                        contentStyle={{ paddingVertical: 10 }}
                        labelStyle={{ fontSize: 16, fontWeight: 'bold', letterSpacing: 0.5 }}
                    >
                        Secure Login
                    </Button>

                    <View style={styles.footer}>
                        <Text variant="bodySmall" style={{ color: '#999', textAlign: 'center' }}>
                            By logging in, you agree to the Hospital Data Policy.
                        </Text>
                    </View>
                </Surface>

                <Text style={{ textAlign: 'center', marginTop: 30, color: '#aaa', fontSize: 12, opacity: 0.7 }}>
                    Powered by CyberHausa HMS v1.2
                </Text>
            </View>

            <Snackbar
                visible={snackbarVisible}
                onDismiss={() => setSnackbarVisible(false)}
                duration={3000}
                style={{ backgroundColor: theme.colors.error, marginBottom: 20 }}
                action={{
                    label: 'Close',
                    onPress: () => setSnackbarVisible(false),
                    textColor: 'white'
                }}
            >
                {snackbarMessage}
            </Snackbar>
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#f4f6f9',
    },
    loadingContainer: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
    },
    loadingContent: {
        alignItems: 'center',
    },
    topSection: {
        height: height * 0.4,
        borderBottomLeftRadius: 30,
        borderBottomRightRadius: 30,
        justifyContent: 'center',
        alignItems: 'center',
        paddingBottom: 50,
    },
    headerContent: {
        alignItems: 'center',
        marginBottom: 20,
    },
    logo: {
        width: 100,
        height: 100,
        marginBottom: 10,
    },
    headerTitle: {
        color: 'white',
        fontWeight: 'bold',
        marginTop: 10,
    },
    headerSubtitle: {
        color: 'rgba(255,255,255,0.9)',
        marginTop: 5,
    },
    bottomSection: {
        flex: 1,
        marginTop: -60, // Overlap effect
        paddingHorizontal: 20,
        paddingBottom: 40, // Add explicit padding for bottom navigation
    },
    card: {
        padding: 25,
        borderRadius: 20,
        backgroundColor: 'white',
    },
    input: {
        marginBottom: 20,
        backgroundColor: 'white',
    },
    button: {
        marginTop: 10,
        borderRadius: 12,
    },
    footer: {
        marginTop: 20,
    }
});
