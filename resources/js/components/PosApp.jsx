import { useState, useEffect, useCallback } from 'react';

const API = '/api';

export default function PosApp() {
    const [menus, setMenus] = useState([]);
    const [categories, setCategories] = useState([]);
    const [activeCategory, setActiveCategory] = useState('all');
    const [cart, setCart] = useState([]);
    const [tableNumber, setTableNumber] = useState(1);
    const [orders, setOrders] = useState([]);
    const [activeTab, setActiveTab] = useState('order'); // order, status
    const [loading, setLoading] = useState(false);

    // Fetch menus
    useEffect(() => {
        fetch(`${API}/menus`)
            .then(r => r.json())
            .then(d => {
                setMenus(d.data);
                setCategories(d.categories);
            });
    }, []);

    // Fetch orders for status board
    useEffect(() => {
        if (activeTab === 'status') {
            fetch(`${API}/orders?per_page=50`)
                .then(r => r.json())
                .then(d => setOrders(d.data));
        }
    }, [activeTab]);

    // Cart operations
    const addToCart = useCallback((menu) => {
        setCart(prev => {
            const existing = prev.find(item => item.id === menu.id);
            if (existing) {
                return prev.map(item =>
                    item.id === menu.id
                        ? { ...item, quantity: item.quantity + 1, subtotal: (item.quantity + 1) * menu.price }
                        : item
                );
            }
            return [...prev, { ...menu, quantity: 1, subtotal: menu.price }];
        });
    }, []);

    const removeFromCart = useCallback((menuId) => {
        setCart(prev => prev.filter(item => item.id !== menuId));
    }, []);

    const updateQuantity = useCallback((menuId, delta) => {
        setCart(prev => prev.map(item => {
            if (item.id !== menuId) return item;
            const newQty = Math.max(1, item.quantity + delta);
            return { ...item, quantity: newQty, subtotal: newQty * menus.find(m => m.id === menuId).price };
        }));
    }, [menus]);

    const cartTotal = cart.reduce((sum, item) => sum + item.subtotal, 0);

    // Submit order
    const submitOrder = async () => {
        if (cart.length === 0) return;
        setLoading(true);
        try {
            const res = await fetch(`${API}/orders`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table_number: tableNumber,
                    items: cart.map(item => ({ menu_id: item.id, quantity: item.quantity })),
                }),
            });
            if (res.ok) {
                setCart([]);
                alert('注文を送信しました！');
            }
        } finally {
            setLoading(false);
        }
    };

    // Update order status
    const updateStatus = async (orderId, newStatus) => {
        await fetch(`${API}/orders/${orderId}/status`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ status: newStatus }),
        });
        setOrders(prev => prev.map(o => o.id === orderId ? { ...o, status: newStatus } : o));
    };

    const filteredMenus = activeCategory === 'all'
        ? menus
        : menus.filter(m => m.category === activeCategory);

    const statusColors = {
        pending: 'bg-yellow-100 text-yellow-800',
        preparing: 'bg-blue-100 text-blue-800',
        served: 'bg-green-100 text-green-800',
        paid: 'bg-gray-100 text-gray-600',
        cancelled: 'bg-red-100 text-red-800',
    };

    const statusLabels = {
        pending: '待ち',
        preparing: '調理中',
        served: '提供済',
        paid: '会計済',
        cancelled: 'キャンセル',
    };

    const categoryLabels = {
        sushi: '🍣 寿司',
        ramen: '🍜 ラーメン',
        donburi: '🍚 丼',
        drink: '🥤 飲み物',
        dessert: '🍡 デザート',
    };

    return (
        <div className="min-h-screen bg-gray-50">
            {/* Header */}
            <header className="bg-white shadow-sm border-b">
                <div className="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
                    <h1 className="text-xl font-bold text-gray-900">🍽️ Smart Restaurant POS</h1>
                    <div className="flex gap-2">
                        <button
                            onClick={() => setActiveTab('order')}
                            className={`px-4 py-2 rounded-lg font-medium ${activeTab === 'order' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'}`}
                        >
                            注文
                        </button>
                        <button
                            onClick={() => setActiveTab('status')}
                            className={`px-4 py-2 rounded-lg font-medium ${activeTab === 'status' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'}`}
                        >
                            注文状況
                        </button>
                    </div>
                </div>
            </header>

            {activeTab === 'order' ? (
                <div className="max-w-7xl mx-auto px-4 py-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Menu Grid */}
                    <div className="lg:col-span-2">
                        {/* Category Tabs */}
                        <div className="flex gap-2 mb-4 overflow-x-auto pb-2">
                            <button
                                onClick={() => setActiveCategory('all')}
                                className={`px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap ${activeCategory === 'all' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border'}`}
                            >
                                すべて
                            </button>
                            {categories.map(cat => (
                                <button
                                    key={cat}
                                    onClick={() => setActiveCategory(cat)}
                                    className={`px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap ${activeCategory === cat ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border'}`}
                                >
                                    {categoryLabels[cat] || cat}
                                </button>
                            ))}
                        </div>

                        {/* Menu Items */}
                        <div className="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            {filteredMenus.map(menu => (
                                <button
                                    key={menu.id}
                                    onClick={() => addToCart(menu)}
                                    className="bg-white rounded-xl p-4 text-left shadow-sm border hover:shadow-md hover:border-blue-300 transition-all"
                                >
                                    <div className="font-medium text-gray-900">{menu.name_jp}</div>
                                    <div className="text-sm text-gray-500">{menu.name_en}</div>
                                    <div className="mt-2 text-lg font-bold text-blue-600">¥{menu.price.toLocaleString()}</div>
                                </button>
                            ))}
                        </div>
                    </div>

                    {/* Cart */}
                    <div className="bg-white rounded-xl shadow-sm border p-4 h-fit sticky top-4">
                        <h2 className="text-lg font-bold mb-4">🛒 カート</h2>

                        {/* Table Number */}
                        <div className="mb-4">
                            <label className="text-sm text-gray-600">テーブル番号</label>
                            <select
                                value={tableNumber}
                                onChange={e => setTableNumber(Number(e.target.value))}
                                className="w-full mt-1 border rounded-lg px-3 py-2"
                            >
                                {Array.from({ length: 12 }, (_, i) => (
                                    <option key={i + 1} value={i + 1}>{i + 1}番テーブル</option>
                                ))}
                            </select>
                        </div>

                        {/* Cart Items */}
                        {cart.length === 0 ? (
                            <div className="text-center text-gray-400 py-8">カートは空です</div>
                        ) : (
                            <div className="space-y-3 mb-4">
                                {cart.map(item => (
                                    <div key={item.id} className="flex items-center justify-between">
                                        <div>
                                            <div className="font-medium text-sm">{item.name_jp}</div>
                                            <div className="text-xs text-gray-500">¥{item.price.toLocaleString()}</div>
                                        </div>
                                        <div className="flex items-center gap-2">
                                            <button
                                                onClick={() => updateQuantity(item.id, -1)}
                                                className="w-7 h-7 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200"
                                            >-</button>
                                            <span className="w-6 text-center text-sm">{item.quantity}</span>
                                            <button
                                                onClick={() => updateQuantity(item.id, 1)}
                                                className="w-7 h-7 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200"
                                            >+</button>
                                            <button
                                                onClick={() => removeFromCart(item.id)}
                                                className="ml-1 text-red-400 hover:text-red-600 text-sm"
                                            >✕</button>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}

                        {/* Total & Submit */}
                        <div className="border-t pt-4">
                            <div className="flex justify-between items-center mb-4">
                                <span className="font-medium">合計</span>
                                <span className="text-2xl font-bold text-blue-600">¥{cartTotal.toLocaleString()}</span>
                            </div>
                            <button
                                onClick={submitOrder}
                                disabled={cart.length === 0 || loading}
                                className="w-full py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed"
                            >
                                {loading ? '送信中...' : '注文する'}
                            </button>
                        </div>
                    </div>
                </div>
            ) : (
                /* Order Status Board */
                <div className="max-w-7xl mx-auto px-4 py-6">
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        {['pending', 'preparing', 'served', 'paid'].map(status => (
                            <div key={status} className="bg-white rounded-xl shadow-sm border p-4">
                                <h3 className={`font-bold mb-3 px-2 py-1 rounded ${statusColors[status]}`}>
                                    {statusLabels[status]} ({orders.filter(o => o.status === status).length})
                                </h3>
                                <div className="space-y-2">
                                    {orders.filter(o => o.status === status).map(order => (
                                        <div key={order.id} className="bg-gray-50 rounded-lg p-3">
                                            <div className="flex justify-between items-center mb-1">
                                                <span className="font-bold">{order.table_number}番テーブル</span>
                                                <span className="text-sm text-gray-500">#{order.id}</span>
                                            </div>
                                            <div className="text-sm text-gray-600">
                                                {order.items?.map(item => (
                                                    <span key={item.id} className="mr-2">
                                                        {item.menu?.name_jp} ×{item.quantity}
                                                    </span>
                                                ))}
                                            </div>
                                            <div className="mt-2 flex gap-1">
                                                {status === 'pending' && (
                                                    <button
                                                        onClick={() => updateStatus(order.id, 'preparing')}
                                                        className="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded"
                                                    >調理開始</button>
                                                )}
                                                {status === 'preparing' && (
                                                    <button
                                                        onClick={() => updateStatus(order.id, 'served')}
                                                        className="text-xs px-2 py-1 bg-green-100 text-green-700 rounded"
                                                    >提供完了</button>
                                                )}
                                                {status === 'served' && (
                                                    <button
                                                        onClick={() => updateStatus(order.id, 'paid')}
                                                        className="text-xs px-2 py-1 bg-gray-200 text-gray-700 rounded"
                                                    >会計完了</button>
                                                )}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            )}
        </div>
    );
}
