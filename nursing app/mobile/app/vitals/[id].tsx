import React, { useState } from 'react';
import { View, StyleSheet, ScrollView } from 'react-native';
import { TextInput, Button, Text, Surface, useTheme, HelperText } from 'react-native-paper';
import { useLocalSearchParams, useRouter } from 'expo-router';
import { SafeAreaView } from 'react-native-safe-area-context';
import api from '../../services/api';

export default function RecordVitals() {
    const { id } = useLocalSearchParams();
    const router = useRouter();
    const theme = useTheme();
    const [loading, setLoading] = useState(false);

    // Vitals State
    const [temp, setTemp] = useState('');
    const [bp, setBp] = useState('');
    const [pulse, setPulse] = useState('');
    const [spo2, setSpo2] = useState('');
    const [resp, setResp] = useState('');
    const [notes, setNotes] = useState('');

    const handleSubmit = async () => {
        if (!temp || !bp || !pulse) {
            alert('Please fill in Temperature, BP, and Pulse');
            return;
        }

        setLoading(true);
        try {
            await api.post(`/visits/${id}/vitals`, {
                temperature: temp,
                blood_pressure: bp,
                pulse: pulse,
                spo2: spo2,
                respiratory_rate: resp,
                notes: notes
            });
            alert('Vitals recorded successfully');
            router.back();
        } catch (error: any) {
            alert('Error recording vitals: ' + (error.response?.data?.message || 'Unknown error'));
        } finally {
            setLoading(false);
        }
    };

    return (
        <SafeAreaView style={styles.container}>
            <View style={styles.header}>
                <Button icon="arrow-left" mode="text" onPress={() => router.back()}>Cancel</Button>
                <Text variant="titleMedium" style={{ fontWeight: 'bold' }}>Record Vitals</Text>
                <Button
                    mode="contained"
                    onPress={handleSubmit}
                    loading={loading}
                    disabled={loading}
                >
                    Save
                </Button>
            </View>

            <ScrollView style={styles.content}>
                <Surface style={styles.card} elevation={1}>
                    <Text variant="titleSmall" style={{ marginBottom: 15, color: theme.colors.primary }}>
                        Vital Signs
                    </Text>

                    <View style={styles.row}>
                        <View style={styles.col}>
                            <TextInput
                                label="Temperature (°C)"
                                value={temp}
                                onChangeText={setTemp}
                                mode="outlined"
                                keyboardType="numeric"
                                right={<TextInput.Icon icon="thermometer" />}
                            />
                        </View>
                        <View style={styles.col}>
                            <TextInput
                                label="BP (mmHg)"
                                value={bp}
                                onChangeText={setBp}
                                mode="outlined"
                                placeholder="120/80"
                                right={<TextInput.Icon icon="heart-pulse" />}
                            />
                        </View>
                    </View>

                    <View style={styles.row}>
                        <View style={styles.col}>
                            <TextInput
                                label="Pulse (bpm)"
                                value={pulse}
                                onChangeText={setPulse}
                                mode="outlined"
                                keyboardType="numeric"
                                right={<TextInput.Icon icon="pulse" />}
                            />
                        </View>
                        <View style={styles.col}>
                            <TextInput
                                label="SPO2 (%)"
                                value={spo2}
                                onChangeText={setSpo2}
                                mode="outlined"
                                keyboardType="numeric"
                                right={<TextInput.Icon icon="water-percent" />}
                            />
                        </View>
                    </View>

                    <TextInput
                        label="Respiratory Rate (optional)"
                        value={resp}
                        onChangeText={setResp}
                        mode="outlined"
                        keyboardType="numeric"
                        style={{ marginTop: 10 }}
                    />
                </Surface>

                <Surface style={styles.card} elevation={1}>
                    <Text variant="titleSmall" style={{ marginBottom: 15, color: theme.colors.primary }}>
                        Observations / Notes
                    </Text>
                    <TextInput
                        label="Additional Notes"
                        value={notes}
                        onChangeText={setNotes}
                        mode="outlined"
                        multiline
                        numberOfLines={4}
                    />
                </Surface>
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
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        padding: 15,
        backgroundColor: 'white',
        borderBottomWidth: 1,
        borderBottomColor: '#eee',
    },
    content: {
        padding: 20,
    },
    card: {
        padding: 20,
        borderRadius: 15,
        backgroundColor: 'white',
        marginBottom: 20,
    },
    row: {
        flexDirection: 'row',
        gap: 15,
        marginBottom: 15,
    },
    col: {
        flex: 1,
    }
});
