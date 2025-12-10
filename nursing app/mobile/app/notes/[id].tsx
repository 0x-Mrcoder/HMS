import React, { useState } from 'react';
import { View, StyleSheet, ScrollView } from 'react-native';
import { TextInput, Button, Text, Surface, useTheme, Chip } from 'react-native-paper';
import { useLocalSearchParams, useRouter } from 'expo-router';
import { SafeAreaView } from 'react-native-safe-area-context';
import api from '../../services/api';

const NOTE_TYPES = ['General', 'Medication', 'Dressing', 'Monitor', 'Incident'];

export default function AddNote() {
    const { id } = useLocalSearchParams();
    const router = useRouter();
    const theme = useTheme();
    const [loading, setLoading] = useState(false);

    const [note, setNote] = useState('');
    const [selectedType, setSelectedType] = useState('General');

    const handleSubmit = async () => {
        if (!note) {
            alert('Please enter a note');
            return;
        }

        setLoading(true);
        try {
            await api.post(`/visits/${id}/notes`, {
                note: `[${selectedType}] ${note}`
            });
            alert('Note saved successfully');
            router.back();
        } catch (error: any) {
            alert('Error saving note: ' + (error.response?.data?.message || 'Unknown error'));
        } finally {
            setLoading(false);
        }
    };

    return (
        <SafeAreaView style={styles.container}>
            <View style={styles.header}>
                <Button icon="close" mode="text" onPress={() => router.back()}>Cancel</Button>
                <Text variant="titleMedium" style={{ fontWeight: 'bold' }}>Nursing Note</Text>
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
                <Text variant="labelLarge" style={{ marginBottom: 10, color: '#666' }}>Note Type</Text>
                <View style={styles.chips}>
                    {NOTE_TYPES.map(type => (
                        <Chip
                            key={type}
                            selected={selectedType === type}
                            onPress={() => setSelectedType(type)}
                            style={{ marginRight: 8, marginBottom: 8 }}
                            showSelectedOverlay
                        >
                            {type}
                        </Chip>
                    ))}
                </View>

                <Surface style={styles.card} elevation={1}>
                    <TextInput
                        placeholder="Type your clinical notes here..."
                        value={note}
                        onChangeText={setNote}
                        mode="flat"
                        multiline
                        numberOfLines={10}
                        style={{ backgroundColor: 'transparent' }}
                        underlineColor="transparent"
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
        padding: 10,
        borderRadius: 15,
        backgroundColor: 'white',
        minHeight: 200,
    },
    chips: {
        flexDirection: 'row',
        flexWrap: 'wrap',
        marginBottom: 20,
    }
});
