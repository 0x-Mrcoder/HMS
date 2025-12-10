import React from 'react';
import { View, StyleSheet, ScrollView, Alert } from 'react-native';
import { Text, Avatar, List, Button, Switch, Divider, Surface, useTheme } from 'react-native-paper';
import { useAuth } from '../../context/AuthContext';
import { SafeAreaView } from 'react-native-safe-area-context';

export default function ProfileScreen() {
    const { user, signOut } = useAuth();
    const theme = useTheme();
    const [isDarkTheme, setIsDarkTheme] = React.useState(false);
    const [notifications, setNotifications] = React.useState(true);

    const handleLogout = () => {
        Alert.alert(
            "Logout",
            "Are you sure you want to log out?",
            [
                { text: "Cancel", style: "cancel" },
                { text: "Logout", style: "destructive", onPress: signOut }
            ]
        );
    };

    return (
        <SafeAreaView style={styles.container}>
            <View style={[styles.header, { backgroundColor: theme.colors.primary }]}>
                <View style={styles.headerContent}>
                    <Avatar.Image
                        size={100}
                        source={{ uri: user?.photo_url || 'https://i.pravatar.cc/300' }}
                        style={styles.avatar}
                    />
                    <Text variant="headlineSmall" style={{ color: 'white', fontWeight: 'bold', marginTop: 10 }}>
                        {user?.name || 'Nurse User'}
                    </Text>
                    <Text variant="bodyLarge" style={{ color: 'rgba(255,255,255,0.9)' }}>
                        {user?.email || 'nurse@hms.com'}
                    </Text>
                    <View style={styles.badge}>
                        <Text style={{ color: theme.colors.primary, fontWeight: 'bold', fontSize: 12 }}>
                            Senior Nurse
                        </Text>
                    </View>
                </View>
            </View>

            <ScrollView style={styles.content}>
                <Surface style={styles.section} elevation={1}>
                    <Text variant="titleMedium" style={styles.sectionTitle}>Current Assignment</Text>
                    <List.Item
                        title="Ward"
                        description="General Ward - Floor 3"
                        left={props => <List.Icon {...props} icon="hospital-building" color={theme.colors.primary} />}
                    />
                    <Divider />
                    <List.Item
                        title="Shift"
                        description="Morning Shift (07:00 - 15:00)"
                        left={props => <List.Icon {...props} icon="clock-outline" color={theme.colors.primary} />}
                    />
                </Surface>

                <Surface style={styles.section} elevation={1}>
                    <Text variant="titleMedium" style={styles.sectionTitle}>Account</Text>
                    <List.Item
                        title="User ID"
                        description={`#${user?.id || '---'}`}
                        left={props => <List.Icon {...props} icon="card-account-details-outline" />}
                    />
                    <Divider />
                    <List.Item
                        title="Email"
                        description={user?.email}
                        left={props => <List.Icon {...props} icon="email-outline" />}
                    />
                </Surface>

                <Button
                    mode="outlined"
                    onPress={handleLogout}
                    style={styles.logoutButton}
                    textColor={theme.colors.error}
                    icon="logout"
                >
                    Sign Out
                </Button>

                <View style={{ height: 40 }} />
            </ScrollView>
        </SafeAreaView>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#f8f9fa',
    },
    header: {
        paddingBottom: 30,
        borderBottomLeftRadius: 30,
        borderBottomRightRadius: 30,
    },
    headerContent: {
        alignItems: 'center',
        marginTop: 20,
    },
    avatar: {
        borderWidth: 4,
        borderColor: 'rgba(255,255,255,0.3)',
    },
    badge: {
        backgroundColor: 'white',
        paddingHorizontal: 12,
        paddingVertical: 4,
        borderRadius: 20,
        marginTop: 10,
    },
    content: {
        flex: 1,
        padding: 20,
        marginTop: -20, // Overlap slightly if needed, but here simple layout
    },
    section: {
        backgroundColor: 'white',
        borderRadius: 15,
        marginBottom: 20,
        paddingVertical: 10,
        overflow: 'hidden',
    },
    sectionTitle: {
        paddingHorizontal: 20,
        paddingVertical: 10,
        color: '#888',
        fontSize: 14,
        fontWeight: 'bold',
        textTransform: 'uppercase',
    },
    logoutButton: {
        borderColor: '#ffdddd',
        backgroundColor: '#fff5f5',
        marginBottom: 20,
    }
});
