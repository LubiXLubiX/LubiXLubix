import React from 'https://esm.sh/react@18.2.0';
import { createRoot } from 'https://esm.sh/react-dom@18.2.0/client';
import LandingPage from './ui/pages/LandingPage.lubix.jsx';

const container = document.getElementById('root');

if (container) {
    const root = createRoot(container);
    
    // Simple Error Boundary
    class ErrorBoundary extends React.Component {
        constructor(props) {
            super(props);
            this.state = { hasError: false, error: null };
        }
        static getDerivedStateFromError(error) {
            return { hasError: true, error };
        }
        render() {
            if (this.state.hasError) {
                return (
                    <div style={{ padding: '20px', color: 'white', background: '#900' }}>
                        <h1>Something went wrong.</h1>
                        <pre>{this.state.error?.toString()}</pre>
                    </div>
                );
            }
            return this.props.children;
        }
    }

    const AppComponent = LandingPage?.default || LandingPage;
    root.render(
        React.createElement(ErrorBoundary, null, 
            React.createElement(AppComponent)
        )
    );
} else {
    console.error('[Deca] Root element #root not found.');
}
