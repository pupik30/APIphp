import React from 'react';
import { SafeAreaView, ScrollView, View, Text, StyleSheet, StatusBar } from 'react-native';
import { priceList, tenants } from './data';

const money = (v) =>
    Number(v).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ' ');

// Ведомость задолжников: задолженность = стоимость квартиры - сумма квартплаты
function buildReport() {
    return tenants
        .filter((t) => t.rent_paid < t.apartment_cost)
        .map((t) => ({ ...t, debt: t.apartment_cost - t.rent_paid }))
        .sort((a, b) => a.owner_name.localeCompare(b.owner_name, 'ru') || a.serial_no - b.serial_no);
}

// columns = [{title, flex, num}], rows = массив массивов, footer - необязательная строка
function Table({ columns, rows, footer }) {
    return (
        <View style={s.table}>
            <View style={[s.row, s.head]}>
                {columns.map((c, i) => (
                    <Text key={i} style={[s.cell, s.headText, { flex: c.flex }, c.num && s.num]}>{c.title}</Text>
                ))}
            </View>
            {rows.map((r, i) => (
                <View key={i} style={s.row}>
                    {r.map((v, j) => (
                        <Text key={j} style={[s.cell, { flex: columns[j].flex }, columns[j].num && s.num, columns[j].debt && s.debt]}>{v}</Text>
                    ))}
                </View>
            ))}
            {footer}
        </View>
    );
}

export default function App() {
    const report = buildReport();
    const total = report.reduce((sum, r) => sum + r.debt, 0);

    return (
        <SafeAreaView style={s.screen}>
            <StatusBar barStyle="dark-content" />
            <ScrollView contentContainerStyle={s.content}>
                <Text style={s.h1}>Ведомость задолжников по квартплате</Text>

                {report.length === 0 ? (
                    <Text style={s.message}>Задолжников нет</Text>
                ) : (
                    <Table
                        columns={[
                            { title: '№', flex: 0.5, num: true },
                            { title: 'ФИО', flex: 1.8 },
                            { title: 'Тип', flex: 1.6 },
                            { title: 'Начислено', flex: 1.2, num: true },
                            { title: 'Внесено', flex: 1.2, num: true },
                            { title: 'Долг', flex: 1.2, num: true, debt: true },
                        ]}
                        rows={report.map((r, i) => [
                            i + 1, r.owner_name, r.apartment_type,
                            money(r.apartment_cost), money(r.rent_paid), money(r.debt),
                        ])}
                        footer={
                            <View style={s.row}>
                                <Text style={[s.cell, s.bold, { flex: 6.3 }]}>Итого задолженность</Text>
                                <Text style={[s.cell, s.bold, s.num, s.debt, { flex: 1.2 }]}>{money(total)}</Text>
                            </View>
                        }
                    />
                )}

                <Text style={s.h2}>Прейскурант</Text>
                <Table
                    columns={[
                        { title: '№', flex: 0.5, num: true },
                        { title: 'Тип квартиры', flex: 2 },
                        { title: 'Метр, руб.', flex: 1.2, num: true },
                        { title: 'Коммун. на чел.', flex: 1.4, num: true },
                    ]}
                    rows={priceList.map((r) => [
                        r.serial_no, r.apartment_type, money(r.price_per_meter), money(r.utilities_per_person),
                    ])}
                />

                <Text style={s.h2}>Квартиросъемщики</Text>
                <Table
                    columns={[
                        { title: '№', flex: 0.5, num: true },
                        { title: 'ФИО', flex: 1.8 },
                        { title: 'Чел.', flex: 0.6, num: true },
                        { title: 'м²', flex: 0.6, num: true },
                        { title: 'Тип', flex: 1.5 },
                        { title: 'Стоимость', flex: 1.1, num: true },
                        { title: 'Оплачено', flex: 1.1, num: true },
                    ]}
                    rows={tenants.map((r) => [
                        r.serial_no, r.owner_name, r.residents_count, r.living_area,
                        r.apartment_type, money(r.apartment_cost), money(r.rent_paid),
                    ])}
                />
            </ScrollView>
        </SafeAreaView>
    );
}

const s = StyleSheet.create({
    screen: { flex: 1, backgroundColor: '#fff', paddingTop: StatusBar.currentHeight || 0 },
    content: { padding: 15 },
    h1: { fontSize: 20, fontWeight: 'bold', marginBottom: 15 },
    h2: { fontSize: 17, fontWeight: 'bold', marginTop: 25, marginBottom: 8 },
    message: { color: '#b00' },
    table: { borderTopWidth: 1, borderLeftWidth: 1, borderColor: '#999' },
    row: { flexDirection: 'row' },
    head: { backgroundColor: '#eee' },
    cell: { padding: 4, fontSize: 11, borderRightWidth: 1, borderBottomWidth: 1, borderColor: '#999' },
    headText: { fontWeight: 'bold' },
    num: { textAlign: 'right' },
    bold: { fontWeight: 'bold' },
    debt: { color: '#b00', fontWeight: 'bold' },
});
