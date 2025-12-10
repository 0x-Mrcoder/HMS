import React, { useEffect, useState } from 'react';
import { View, StyleSheet, ScrollView } from 'react-native';
import { Text, Avatar, Button, Card, DataTable, FAB, ActivityIndicator, Divider, Chip, useTheme } from 'react-native-paper';
import { useLocalSearchParams, useRouter } from 'expo-router';
import { SafeAreaView } from 'react-native-safe-area-context';
import api from '../../../services/api';

export default function PatientDetail() {
    const { id } = useLocalSearchParams();
    const router = useRouter();
    const [visit, setVisit] = useState<any>(null);
    const [loading, setLoading] = useState(true);
    const [fabOpen, setFabOpen] = useState(false);
    const theme = useTheme();

    useEffect(() => {
        fetchDetail();
    }, [id]);

    const fetchDetail = async () => {
        try {
            const response = await api.get(`/patients/${id}`);
            setVisit(response.data);
        } catch (error) {
            console.log(error);
        } finally {
            setLoading(false);
        }
    };

    if (loading) {
        return (
            <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
                <ActivityIndicator size="large" />
            </View>
        );
    }

    if (!visit) {
        return (
            <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
                <Text>Patient not found</Text>
            </View>
        );
    }

    return (
        <SafeAreaView style={{ flex: 1, backgroundColor: '#f5f5f5' }}>
            <ScrollView>
                <View style={styles.header}>
                    <View style={styles.profileHeader}>
                        <Avatar.Text size={80} label={visit.patient?.first_name?.[0] || 'P'} style={{ backgroundColor: theme.colors.primary }} />
                        <View style={{ marginLeft: 16 }}>
                            <Text variant="headlineSmall" style={{ fontWeight: 'bold' }}>{visit.patient?.first_name} {visit.patient?.last_name}</Text>
                            <Text variant="bodyMedium" style={{ color: '#666' }}>ID: {visit.patient?.hospital_id}</Text>
                            <Chip icon="bed" style={{ marginTop: 8 }}>{visit.ward?.name} - {visit.bed?.number}</Chip>
                        </View>
                    </View>
                </View>

                <View style={styles.section}>
                    <Text variant="titleMedium" style={styles.sectionTitle}>Current Vitals</Text>
                    <Card style={styles.card}>
                        <Card.Content>
                            <View style={styles.vitalsGrid}>
                                <View style={styles.vitalItem}>
                                    <Text variant="labelMedium" style={{ color: '#666' }}>BP</Text>
                                    <Text variant="titleLarge">{visit.vitals?.blood_pressure || '--'}</Text>
                                </View>
                                <View style={styles.vitalItem}>
                                    <Text variant="labelMedium" style={{ color: '#666' }}>Temp</Text>
                                    <Text variant="titleLarge">{visit.vitals?.temperature ? `${visit.vitals.temperature}°C` : '--'}</Text>
                                </View>
                                <View style={styles.vitalItem}>
                                    <Text variant="labelMedium" style={{ color: '#666' }}>Pulse</Text>
                                    <Text variant="titleLarge">{visit.vitals?.pulse || '--'}</Text>
                                </View>
                                <View style={styles.vitalItem}>
                                    <Text variant="labelMedium" style={{ color: '#666' }}>SPO2</Text>
                                    <Text variant="titleLarge">{visit.vitals?.spo2 ? `${visit.vitals.spo2}%` : '--'}</Text>
                                </View>
                            </View>
                            <Text variant="bodySmall" style={{ color: '#999', marginTop: 10 }}>
                                Last checked: {visit.vitals?.recorded_at ? new Date(visit.vitals.recorded_at).toLocaleString() : 'Never'}
                            </Text>
                        </Card.Content>
                    </Card>
                </View>

                <View style={styles.section}>
                    <Text variant="titleMedium" style={styles.sectionTitle}>Prescriptions</Text>
                    {visit.prescriptions?.length > 0 ? (
                        visit.prescriptions.map((p: any, index: number) => (
                            <Card key={index} style={styles.medCard}>
                                <Card.Content style={styles.medContent}>
                                    <View>
                                        <Text variant="titleMedium" style={{ fontWeight: 'bold' }}>{p.drug?.name}</Text>
                                        <Text variant="bodyMedium">{p.dosage}, {p.frequency}</Text>
                                    </View>
                                    <Button mode="contained-tonal" compact>Administer</Button>
                                </Card.Content>
                            </Card>
                        ))
                    ) : (
                        <Text style={{ color: '#999' }}>No active prescriptions.</Text>
                    )}
                </View>

                <View style={styles.section}>
                    <Text variant="titleMedium" style={styles.sectionTitle}>Nursing Notes</Text>
                    {visit.nursing_notes?.length > 0 ? (
                        visit.nursing_notes.map((note: any, index: number) => (
                            <Card key={index} style={styles.noteCard}>
                                <Card.Content>
                                    <Text variant="bodyMedium">{note.note}</Text>
                                    <Text variant="bodySmall" style={{ color: '#999', marginTop: 4 }}>
                                        {new Date(note.created_at).toLocaleString()}
                                    </Text>
                                </Card.Content>
                            </Card>
                        ))
                    ) : (
                        <Text style={{ color: '#999' }}>No notes yet.</Text>
                    )}
                </View>

                <View style={{ height: 80 }} />
            </ScrollView>

            <FAB.Group
                open={fabOpen}
                visible
                icon={fabOpen ? 'close' : 'plus'}
                actions={[
                    { icon: 'pill', label: 'Medications', onPress: () => router.push(`/medications/${visit.id}`) },
                    { icon: 'note-plus', label: 'Add Note', onPress: () => router.push(`/notes/${visit.id}`) },
                    { icon: 'heart-pulse', label: 'Record Vitals', onPress: () => router.push(`/vitals/${visit.id}`) },
                ]}
                onStateChange={({ open }) => setFabOpen(open)}
                onPress={() => {
                    if (fabOpen) {
                        // do something if the speed dial is open
                    }
                }}
                style={styles.fab}
            />
        </SafeAreaView >
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#f8f9fa',
    },
    header: {
        padding: 24,
        backgroundColor: '#fff',
        borderBottomWidth: 1,
        borderBottomColor: '#eee',
    },
    profileHeader: {
        flexDirection: 'row',
        alignItems: 'center',
    },
    tags: {
        flexDirection: 'row',
        marginTop: 8,
        gap: 8,
    },
    tag: {
        backgroundColor: '#f1f5f9',
        paddingHorizontal: 8,
        paddingVertical: 2,
        borderRadius: 4,
        fontSize: 12,
        color: '#666',
    },
    section: {
        padding: 20,
        paddingBottom: 0,
    },
    sectionTitle: {
        marginBottom: 12,
        fontWeight: 'bold',
    },
    card: {
        backgroundColor: '#fff',
    },
    vitalsGrid: {
        flexDirection: 'row',
        justifyContent: 'space-between',
    },
    vitalItem: {
        alignItems: 'center',
    },
    medCard: {
        marginBottom: 10,
        backgroundColor: '#fff',
    },
    medContent: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
    },
    noteCard: {
        marginBottom: 10,
        backgroundColor: '#fff',
        borderLeftWidth: 4,
        borderLeftColor: '#4f46e5',
    },
    fab: {
        position: 'absolute',
        paddingBottom: 20,
        right: 0,
        bottom: 0,
    }
});
