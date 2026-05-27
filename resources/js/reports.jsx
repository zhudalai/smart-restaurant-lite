import './bootstrap';
import { createRoot } from 'react-dom/client';
import ReportsApp from './components/ReportsApp';

const container = document.getElementById('reports-app');
if (container) {
    createRoot(container).render(<ReportsApp />);
}
