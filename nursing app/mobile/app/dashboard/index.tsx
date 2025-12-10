import React, { useEffect, useState } from 'react';
import { View, StyleSheet, ScrollView, RefreshControl } from 'react-native';
import { Text, Card, Avatar, Button, ActivityIndicator, useTheme, Surface, IconButton } from 'react-native-paper';
import { useRouter } from 'expo-router';
import { useAuth } from '../../context/AuthContext';
import { SafeAreaView } from 'react-native-safe-area-context';
import api from '../../services/api';

export default function Dashboard() {
    const { user } = useAuth();
    const router = useRouter();
    const theme = useTheme();
    const [stats, setStats] = useState<any>({ admitted_patients: 0, pending_tasks: 0 });
    const [loading, setLoading] = useState(true);
    const [refreshing, setRefreshing] = useState(false);

    const fetchStats = async () => {
        try {
            const response = await api.get('/dashboard');
            setStats(response.data);
        } catch (error) {
            console.log('Error fetching stats:', error);
        } finally {
            setLoading(false);
            setRefreshing(false);
        }
    };

    useEffect(() => {
        fetchStats();
    }, []);

    const onRefresh = React.useCallback(() => {
        setRefreshing(true);
        fetchStats();
    }, []);

    const MENU_ITEMS = [
        { icon: 'account-group', label: 'My Patients', route: '/dashboard/patients', color: '#4f46e5' },
        { icon: 'calendar-check', label: 'My Shift', route: '/dashboard/profile', color: '#0ea5e9' },
        { icon: 'pill', label: 'Medications', route: '/dashboard/patients', color: '#f59e0b' },
        { icon: 'heart-pulse', label: 'Record Vitals', route: '/dashboard/patients', color: '#ef4444' },
    ];

    return (
        <SafeAreaView style={styles.container}>
            {/* Header */}
            <View style={styles.header}>
                <View>
                    <Text variant="titleMedium" style={{ color: '#64748b', fontSize: 13, textTransform: 'uppercase', letterSpacing: 1 }}>
                        {new Date().toLocaleDateString('en-US', { weekday: 'long', day: 'numeric', month: 'long' })}
                    </Text>
                    <Text variant="headlineMedium" style={{ fontWeight: 'bold', color: '#1e293b', marginTop: 4 }}>
                        Hello, {user?.name?.split(' ')[0] || 'Nurse'}
                    </Text>
                </View>
                <Avatar.Image size={48} source={{ uri: user?.photo_url }} style={{ backgroundColor: '#f1f5f9' }} />
            </View>

            <ScrollView
                showsVerticalScrollIndicator={false}
                contentContainerStyle={styles.content}
                refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
            >
                {/* Stats Overview */}
                <View style={styles.statsRow}>
                    <Surface style={[styles.statCard, { backgroundColor: '#e0e7ff' }]} elevation={0}>
                        <View style={[styles.iconCircle, { backgroundColor: '#4f46e5' }]}>
                            <Avatar.Icon size={24} icon="bed-empty" color="white" style={{ backgroundColor: 'transparent' }} />
                        </View>
                        <Text variant="displaySmall" style={{ fontWeight: 'bold', marginTop: 15, color: '#312e81' }}>
                            {stats.admitted_patients}
                        </Text>
                        <Text variant="labelLarge" style={{ color: '#4338ca' }}>Admitted Patients</Text>
                    </Surface>

                    <Surface style={[styles.statCard, { backgroundColor: '#fff7ed' }]} elevation={0}>
                        <View style={[styles.iconCircle, { backgroundColor: '#f97316' }]}>
                            <Avatar.Icon size={24} icon="clipboard-list" color="white" style={{ backgroundColor: 'transparent' }} />
                        </View>
                        <Text variant="displaySmall" style={{ fontWeight: 'bold', marginTop: 15, color: "#7c2d12" }}>
                            {stats.pending_tasks}
                        </Text>
                        <Text variant="labelLarge" style={{ color: "#9a3412" }}>Pending Tasks</Text>
                    </Surface>
                </View>

                {/* Quick Actions Grid */}
                <Text variant="titleLarge" style={styles.sectionTitle}>Quick Actions</Text>
                <View style={styles.gridContainer}>
                    {MENU_ITEMS.map((item, index) => (
                        <Surface
                            key={index}
                            style={styles.gridItem}
                            elevation={3}
                            onTouchEnd={() => router.push(item.route as any)}
                        >
                            <View style={[styles.iconBox, { backgroundColor: item.color + '15' }]}>
                                <Avatar.Icon size={32} icon={item.icon} color={item.color} style={{ backgroundColor: 'transparent' }} />
                            </View>
                            <Text variant="titleMedium" style={{ marginTop: 12, fontWeight: '600', fontSize: 15 }}>{item.label}</Text>
                        </Surface>
                    ))}
                </View>

                {/* Recent Activity / Next Task */}
                <View style={{ flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 15 }}>
                    <Text variant="titleLarge" style={{ fontWeight: 'bold', color: '#1e293b' }}>Up Next</Text>
                    <Button compact mode="text" textColor={theme.colors.primary}>See All</Button>
                </View>

                <Card style={styles.taskCard} mode="elevated" elevation={2}>
                    <Card.Content style={{ flexDirection: 'row', alignItems: 'center' }}>
                        <View style={[styles.taskIcon, { backgroundColor: '#eef2ff' }]}>
                            <Avatar.Icon size={24} icon="heart-pulse" color="#4f46e5" style={{ backgroundColor: 'transparent' }} />
                        </View>
                        <View style={{ flex: 1, marginLeft: 15 }}>
                            <Text variant="titleMedium" style={{ fontWeight: 'bold' }}>Vitals Check - Room 302</Text>
                            <Text variant="bodyMedium" style={{ color: '#64748b', marginTop: 2 }}>Due in 15 mins</Text>
                        </View>
                        <Button mode="contained-tonal" compact uppercase={false} style={{ borderRadius: 8 }}>Start</Button>
                    </Card.Content>
                </Card>
                <Card style={styles.taskCard} mode="elevated" elevation={2}>
                    <Card.Content style={{ flexDirection: 'row', alignItems: 'center' }}>
                        <View style={[styles.taskIcon, { backgroundColor: '#fff1f2' }]}>
                            <Avatar.Icon size={24} icon="pill" color="#be123c" style={{ backgroundColor: 'transparent' }} />
                        </View>
                        <View style={{ flex: 1, marginLeft: 15 }}>
                            <Text variant="titleMedium" style={{ fontWeight: 'bold' }}>Medication - John Doe</Text>
                            <Text variant="bodyMedium" style={{ color: '#64748b', marginTop: 2 }}>Paracetamol 500mg</Text>
                        </View>
                        <Button mode="contained-tonal" compact uppercase={false} icon="check" style={{ borderRadius: 8 }}>Done</Button>
                    </Card.Content>
                </Card>

                <View style={{ height: 40 }} />
            </ScrollView>
        </SafeAreaView>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#f8fafc',
    },
    header: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        paddingHorizontal: 24,
        paddingTop: 15,
        paddingBottom: 20,
        backgroundColor: '#fff',
        borderBottomWidth: 1,
        borderBottomColor: '#f1f5f9',
    },
    content: {
        padding: 24,
    },
    statsRow: {
        flexDirection: 'row',
        gap: 16,
        marginBottom: 32,
    },
    statCard: {
        flex: 1,
        padding: 20,
        borderRadius: 24,
        alignItems: 'flex-start',
        // No shadow for flat sleek look on stats
    },
    iconCircle: {
        width: 40,
        height: 40,
        borderRadius: 12,
        justifyContent: 'center',
        alignItems: 'center',
    },
    sectionTitle: {
        fontWeight: 'bold',
        marginBottom: 16,
        color: '#1e293b',
    },
    gridContainer: {
        flexDirection: 'row',
        flexWrap: 'wrap',
        gap: 16,
        marginBottom: 32,
    },
    gridItem: {
        width: '47%',
        backgroundColor: 'white',
        borderRadius: 20,
        padding: 20,
        alignItems: 'center',
        shadowColor: '#64748b',
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.05,
        shadowRadius: 10,
    },
    iconBox: {
        padding: 12,
        borderRadius: 16,
        marginBottom: 8,
    },
    taskCard: {
        marginBottom: 16,
        backgroundColor: 'white',
        borderRadius: 16,
        shadowColor: '#64748b',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.05,
        shadowRadius: 4,
    },
    taskIcon: {
        width: 40,
        height: 40,
        borderRadius: 10,
        justifyContent: 'center',
        alignItems: 'center',
    }
});
