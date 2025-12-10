import React, { useState } from 'react';
import { View, StyleSheet, ScrollView } from 'react-native';
import { Text, TextInput, Button, HelperText } from 'react-native-paper';
import { useLocalSearchParams, useRouter } from 'expo-router';
import { SafeAreaView } from 'react-native-safe-area-context';
import api from '../../services/api';

export default function VitalsForm() {
    const { visitId } = useLocalSearchParams();
    const router = useRouter();
    const [loading, setLoading] = useState(false);

    const [temp, setTemp] = useState('');
    const [bp, setBp] = useState('');
    const [pulse, setPulse] = useState('');
    const [notes, setNotes] = useState('');

    const handleSubmit = async () => {
        setLoading(true);
        try {
            await api.post(`/visits/${visitId}/vitals`, {
                temperature: temp,
                blood_pressure: bp,
                pulse: pulse,
                notes: notes,
            });
            router.back();
        } catch (error) {
            console.error(error);
        } finally {
            setLoading(false);
        }
    };

    return (
        <SafeAreaView style={styles.container}>
            <View style={styles.header}>
                <Text variant="headlineSmall" style={{ fontWeight: 'bold' }}>Record Vitals</Text>
            </View>

            <ScrollView contentContainerStyle={styles.content}>
                <TextInput
                    label="Temperature (°C)"
                    value={temp}
                    onChangeText={setTemp}
                    mode="outlined"
                    keyboardType="numeric"
                    style={styles.input}
                />

                <TextInput
                    label="Blood Pressure (mmHg)"
                    value={bp}
                    onChangeText={setBp}
                    mode="outlined"
                    placeholder="120/80"
                    style={styles.input}
                />

                <TextInput
                    label="Pulse Rate (bpm)"
                    value={pulse}
                    onChangeText={setPulse}
                    mode="outlined"
                    keyboardType="numeric"
                    style={styles.input}
                />

                <TextInput
                    label="Additional Notes"
                    value={notes}
                    onChangeText={setNotes}
                    mode="outlined"
                    multiline
                    numberOfLines={4}
                    style={styles.input}
                />

                <Button
                    mode="contained"
                    onPress={handleSubmit}
                    loading={loading}
                    style={styles.button}
                    contentStyle={{ paddingVertical: 6 }}
                >
                    Save Vitals
                </Button>

                <Button
                    mode="text"
                    onPress={() => router.back()}
                    style={{ marginTop: 10 }}
                >
                    Cancel
                </Button>
            </ScrollView>
        </SafeAreaView>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#fff',
    },
    header: {
        padding: 20,
        borderBottomWidth: 1,
        borderBottomColor: '#eee',
    },
    content: {
        padding: 20,
    },
    input: {
        marginBottom: 16,
    },
    button: {
        marginTop: 8,
        borderRadius: 8,
    },
});
