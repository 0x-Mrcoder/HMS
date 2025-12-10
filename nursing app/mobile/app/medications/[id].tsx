import React, { useEffect, useState } from 'react';
import { View, StyleSheet, FlatList, Alert } from 'react-native';
import { Text, Button, Card, Avatar, useTheme, ActivityIndicator, Portal, Modal, TextInput } from 'react-native-paper';
import { useLocalSearchParams, useRouter } from 'expo-router';
import { SafeAreaView } from 'react-native-safe-area-context';
import api from '../../services/api';

export default function Medications() {
    const { id } = useLocalSearchParams(); // visitId
    const router = useRouter();
    const theme = useTheme();

    const [loading, setLoading] = useState(true);
    const [meds, setMeds] = useState<any[]>([]);
    const [visit, setVisit] = useState<any>(null);

    // Administer Modal
    const [visible, setVisible] = useState(false);
    const [selectedMed, setSelectedMed] = useState<any>(null);
    const [notes, setNotes] = useState('');
    const [administering, setAdministering] = useState(false);

    useEffect(() => {
        fetchMedications();
    }, [id]);

    const fetchMedications = async () => {
        try {
            const response = await api.get(`/visits/${id}/medications`);
            setMeds(response.data.data);
            setVisit(response.data.visit);
        } catch (error) {
            console.log(error);
            Alert.alert("Error", "Failed to load medications");
        } finally {
            setLoading(false);
        }
    };

    const handleAdministerClick = (med: any) => {
        setSelectedMed(med);
        setNotes('');
        setVisible(true);
    };

    const confirmAdminister = async () => {
        if (!selectedMed) return;
        setAdministering(true);
        try {
            await api.post(`/visits/${id}/medications`, {
                drug_name: selectedMed.drug?.name || selectedMed.drug_name,
                dosage: selectedMed.dosage,
                route: 'Oral', // Hardcoded for now, could be dynamic
                notes: notes
            });
            Alert.alert("Success", "Medication recorded successfully");
            setVisible(false);
            fetchMedications(); // Refresh to update "Last Given"
        } catch (error) {
            Alert.alert("Error", "Failed to record medication");
        } finally {
            setAdministering(false);
        }
    };

    const renderItem = ({ item }: { item: any }) => (
        <Card style={styles.card} mode="elevated" elevation={1}>
            <Card.Title
                title={item.drug?.name || item.drug_name}
                subtitle={`${item.dosage} • ${item.frequency}`}
                left={(props) => <Avatar.Icon {...props} icon="pill" style={{ backgroundColor: theme.colors.primaryContainer }} color={theme.colors.primary} />}
            />
            <Card.Content>
                <View style={styles.row}>
                    <Text variant="bodyMedium" style={{ color: '#666' }}>
                        Last Given: {item.last_given_at ? new Date(item.last_given_at).toLocaleString() : 'Never'}
                    </Text>
                </View>
                <Divider style={{ marginVertical: 12 }} />
                <Button
                    mode="contained"
                    onPress={() => handleAdministerClick(item)}
                    icon="check"
                    buttonColor={theme.colors.primary}
                >
                    Administer
                </Button>
            </Card.Content>
        </Card>
    );

    const Divider = ({ style }: any) => <View style={[{ height: 1, backgroundColor: '#eee' }, style]} />;

    if (loading) {
        return (
            <SafeAreaView style={styles.container}>
                <ActivityIndicator size="large" style={{ marginTop: 50 }} />
            </SafeAreaView>
        );
    }

    return (
        <SafeAreaView style={styles.container}>
            <View style={styles.header}>
                <Text variant="headlineSmall" style={{ fontWeight: 'bold' }}>Medication Schedule</Text>
                <Text variant="bodyMedium" style={{ color: '#666' }}>Patient: {visit?.patient?.first_name} {visit?.patient?.last_name}</Text>
            </View>

            <FlatList
                data={meds}
                renderItem={renderItem}
                keyExtractor={(item) => item.id.toString()}
                contentContainerStyle={styles.list}
                ListEmptyComponent={
                    <View style={styles.empty}>
                        <Text>No active prescriptions found.</Text>
                    </View>
                }
            />

            <Portal>
                <Modal visible={visible} onDismiss={() => setVisible(false)} contentContainerStyle={styles.modal}>
                    <Text variant="titleLarge" style={{ marginBottom: 15 }}>Administer {selectedMed?.drug_name}</Text>
                    <Text variant="bodyMedium" style={{ marginBottom: 5 }}>Dosage: {selectedMed?.dosage}</Text>

                    <TextInput
                        label="Notes (Optional)"
                        value={notes}
                        onChangeText={setNotes}
                        mode="outlined"
                        multiline
                        numberOfLines={3}
                        style={{ marginVertical: 15 }}
                    />

                    <Button
                        mode="contained"
                        onPress={confirmAdminister}
                        loading={administering}
                        disabled={administering}
                    >
                        Confirm Administration
                    </Button>
                </Modal>
            </Portal>
        </SafeAreaView>
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
    list: {
        padding: 16,
    },
    card: {
        marginBottom: 16,
        backgroundColor: '#fff',
    },
    row: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
    },
    modal: {
        backgroundColor: 'white',
        padding: 20,
        margin: 20,
        borderRadius: 8,
    },
    empty: {
        alignItems: 'center',
        marginTop: 50,
    }
});
