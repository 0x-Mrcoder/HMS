import React, { useEffect, useState } from 'react';
import { View, StyleSheet, FlatList, TouchableOpacity } from 'react-native';
import { Text, Avatar, Searchbar, Chip } from 'react-native-paper';
import { useRouter } from 'expo-router';
import { SafeAreaView } from 'react-native-safe-area-context';
import api from '../../../services/api';

export default function PatientList() {
    const router = useRouter();
    const [patients, setPatients] = useState<any[]>([]);
    const [loading, setLoading] = useState(true);
    const [searchQuery, setSearchQuery] = useState('');

    useEffect(() => {
        fetchPatients();
    }, []);

    const fetchPatients = async () => {
        try {
            const response = await api.get('/patients');
            setPatients(response.data.data);
        } catch (error) {
            console.log(error);
        } finally {
            setLoading(false);
        }
    };

    const renderItem = ({ item }: { item: any }) => (
        <TouchableOpacity
            style={styles.card}
            onPress={() => router.push({ pathname: '/dashboard/patients/[id]', params: { id: item.id } })}
        >
            <Avatar.Image size={50} source={{ uri: item.patient?.photo_url }} />
            <View style={styles.cardInfo}>
                <Text variant="titleMedium" style={{ fontWeight: 'bold' }}>
                    {item.patient?.first_name} {item.patient?.last_name}
                </Text>
                <Text variant="bodySmall" style={{ color: '#666' }}>
                    ID: {item.patient?.hospital_id} • Age: {new Date().getFullYear() - new Date(item.patient?.date_of_birth).getFullYear()}
                </Text>
                <View style={styles.badges}>
                    <Chip icon="bed" mode="outlined" compact textStyle={{ fontSize: 10 }} style={styles.chip}>
                        {item.ward?.name} - {item.bed?.number}
                    </Chip>
                </View>
            </View>
            <Avatar.Icon size={24} icon="chevron-right" style={{ backgroundColor: 'transparent' }} color="#ccc" />
        </TouchableOpacity >
    );

    return (
        <SafeAreaView style={styles.container}>
            <View style={styles.header}>
                <Text variant="headlineSmall" style={{ fontWeight: 'bold', marginBottom: 16 }}>Admitted Patients</Text>
                <Searchbar
                    placeholder="Search patient..."
                    onChangeText={setSearchQuery}
                    value={searchQuery}
                    style={styles.searchBar}
                />
            </View>

            <FlatList
                data={patients}
                renderItem={renderItem}
                keyExtractor={item => item.id.toString()}
                contentContainerStyle={styles.list}
                refreshing={loading}
                onRefresh={fetchPatients}
            />
        </SafeAreaView>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#f8f9fa',
    },
    header: {
        padding: 20,
        backgroundColor: '#fff',
        borderBottomWidth: 1,
        borderBottomColor: '#eee',
    },
    searchBar: {
        backgroundColor: '#f1f5f9',
        borderRadius: 12,
    },
    list: {
        padding: 20,
    },
    card: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#fff',
        padding: 16,
        borderRadius: 16,
        marginBottom: 12,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.05,
        shadowRadius: 4,
        elevation: 2,
    },
    cardInfo: {
        flex: 1,
        marginLeft: 16,
    },
    badges: {
        flexDirection: 'row',
        marginTop: 8,
    },
    chip: {
        borderColor: '#e2e8f0',
    }
});
