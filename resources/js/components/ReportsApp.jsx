import { useState, useEffect } from 'react';

const API = '/api';

export default function ReportsApp() {
    const [reports, setReports] = useState([]);
    const [loading, setLoading] = useState(false);
    const [generating, setGenerating] = useState(false);
    const [selectedDate, setSelectedDate] = useState(
        new Date(Date.now() - 86400000).toISOString().split('T')[0]
    );

    useEffect(() => {
        fetchReports();
    }, []);

    const fetchReports = async () => {
        setLoading(true);
        try {
            const res = await fetch(`${API}/reports`);
            const data = await res.json();
            setReports(data.data || []);
        } finally {
            setLoading(false);
        }
    };

    const generateReport = async () => {
        setGenerating(true);
        try {
            await fetch(`${API}/reports/generate`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ date: selectedDate }),
            });
            await fetchReports();
        } finally {
            setGenerating(false);
        }
    };

    return (
        <div className="min-h-screen bg-gray-50">
            <header className="bg-white shadow-sm border-b">
                <div className="max-w-7xl mx-auto px-4 py-3">
                    <h1 className="text-xl font-bold text-gray-900">📊 営業日報</h1>
                </div>
            </header>

            <div className="max-w-7xl mx-auto px-4 py-6">
                {/* Generate Report Section */}
                <div className="bg-white rounded-xl shadow-sm border p-6 mb-6">
                    <h2 className="text-lg font-bold mb-4">AI 日報生成</h2>
                    <div className="flex gap-4 items-end">
                        <div>
                            <label className="text-sm text-gray-600">日付</label>
                            <input
                                type="date"
                                value={selectedDate}
                                onChange={e => setSelectedDate(e.target.value)}
                                className="mt-1 border rounded-lg px-3 py-2"
                            />
                        </div>
                        <button
                            onClick={generateReport}
                            disabled={generating}
                            className="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 disabled:bg-gray-300"
                        >
                            {generating ? '生成中...' : 'AI 日報を生成'}
                        </button>
                    </div>
                </div>

                {/* Reports List */}
                {loading ? (
                    <div className="text-center py-12 text-gray-400">読み込み中...</div>
                ) : reports.length === 0 ? (
                    <div className="text-center py-12 text-gray-400">日報がありません。「AI 日報を生成」をクリックしてください。</div>
                ) : (
                    <div className="space-y-4">
                        {reports.map(report => (
                            <div key={report.id} className="bg-white rounded-xl shadow-sm border p-6">
                                <div className="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 className="text-lg font-bold">{report.report_date}</h3>
                                        <div className="flex gap-6 mt-2 text-sm text-gray-600">
                                            <span>売上: <strong className="text-blue-600">¥{Number(report.total_revenue).toLocaleString()}</strong></span>
                                            <span>注文数: <strong>{report.order_count}件</strong></span>
                                            <span>平均単価: <strong>¥{Number(report.avg_order_value).toLocaleString()}</strong></span>
                                        </div>
                                    </div>
                                </div>

                                {report.ai_summary_jp && (
                                    <div className="bg-blue-50 rounded-lg p-4 mb-4">
                                        <div className="text-sm font-medium text-blue-800 mb-1">🤖 AI サマリー</div>
                                        <div className="text-sm text-blue-900">{report.ai_summary_jp}</div>
                                    </div>
                                )}

                                {report.top_items && report.top_items.length > 0 && (
                                    <div>
                                        <div className="text-sm font-medium text-gray-700 mb-2">トップ商品</div>
                                        <div className="flex gap-2 flex-wrap">
                                            {report.top_items.map((item, i) => (
                                                <span key={i} className="px-3 py-1 bg-gray-100 rounded-full text-sm">
                                                    {item.name} ×{item.quantity}
                                                </span>
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </div>
    );
}
