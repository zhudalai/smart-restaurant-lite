import './bootstrap';
import { createRoot } from 'react-dom/client';
import PosApp from './components/PosApp';

const container = document.getElementById('pos-app');
if (container) {
    const root = createRoot(container);
    root.render(<PosApp />);
}
