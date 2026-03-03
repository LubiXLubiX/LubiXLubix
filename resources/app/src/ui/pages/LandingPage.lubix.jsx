import React, { useState, useEffect } from 'https://esm.sh/react@18.2.0';

// Minimalistic SVG Icons
const Icon = ({ children, ...props }) => (
  <svg 
    xmlns="http://www.w3.org/2000/svg" 
    viewBox="0 0 24 24" 
    fill="none" 
    stroke="currentColor" 
    strokeWidth="2" 
    strokeLinecap="round" 
    strokeLinejoin="round" 
    {...props}
  >
    {children}
  </svg>
);

const Terminal = (props) => <Icon {...props}><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></Icon>;
const Zap = (props) => <Icon {...props}><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></Icon>;
const Shield = (props) => <Icon {...props}><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></Icon>;
const Box = (props) => <Icon {...props}><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4a2 2 0 0 0 1-1.73z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></Icon>;
const Cpu = (props) => <Icon {...props}><rect x="4" y="4" width="16" height="16" rx="2" ry="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="15" x2="23" y2="15"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="15" x2="4" y2="15"/></Icon>;
const Layers = (props) => <Icon {...props}><polygon points="12 2 2 7 12 12 22 7 12 2"/><polygon points="2 12 12 17 22 12"/><polygon points="2 17 12 22 22 17"/></Icon>;
const ArrowRight = (props) => <Icon {...props}><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></Icon>;
const BookOpen = (props) => <Icon {...props}><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></Icon>;
const Github = (props) => <Icon {...props}><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 3.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/></Icon>;
const Menu = (props) => <Icon {...props}><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></Icon>;
const X = (props) => <Icon {...props}><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></Icon>;
const CheckCircle2 = (props) => <Icon {...props}><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></Icon>;
const Code2 = (props) => <Icon {...props}><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></Icon>;
const Database = (props) => <Icon {...props}><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></Icon>;
const Blocks = (props) => (
  <Icon {...props}>
    <rect x="3" y="3" width="7" height="7" rx="1" />
    <rect x="14" y="3" width="7" height="7" rx="1" />
    <rect x="14" y="14" width="7" height="7" rx="1" />
    <rect x="3" y="14" width="7" height="7" rx="1" />
  </Icon>
);
const LayoutTemplate = (props) => <Icon {...props}><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><path d="M3 9h18"/><path d="M9 21V9"/></Icon>;
const ChevronRight = (props) => <Icon {...props}><polyline points="9 18 15 12 9 6"/></Icon>;
const Sparkles = (props) => <Icon {...props}><path d="m12 3 1.912 5.813a2 2 0 0 0 1.275 1.275L21 12l-5.813 1.912a2 2 0 0 0-1.275 1.275L12 21l-1.912-5.813a2 2 0 0 0-1.275-1.275L3 12l5.813-1.912a2 2 0 0 0 1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></Icon>;
const Command = (props) => <Icon {...props}><path d="M18 3a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3 3 3 0 0 0 3-3 3 3 0 0 0-3-3H6a3 3 0 0 0-3 3 3 3 0 0 0 3 3 3 3 0 0 0 3-3V6a3 3 0 0 0-3-3 3 3 0 0 0-3 3 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 3 3 0 0 0-3-3z"/></Icon>;

// Utility function for copying text
const copyToClipboard = (text) => {
  const textArea = document.createElement("textarea");
  textArea.value = text;
  textArea.style.position = "fixed"; 
  textArea.style.left = "-999999px";
  document.body.appendChild(textArea);
  textArea.focus();
  textArea.select();
  try { document.execCommand('copy'); } catch (err) {}
  document.body.removeChild(textArea);
};

export default function App() {
  const [currentView, setCurrentView] = useState('landing');
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);

  // Favicon dynamically set to use the exact SVG file
  useEffect(() => {
    const link = document.querySelector("link[rel~='icon']") || document.createElement('link');
    link.rel = 'icon';
    link.type = 'image/svg+xml';
    link.href = '/lubixlogo.svg'; 
    document.head.appendChild(link);

    // Sinkronisasi Tema dengan Sistem (Light/Dark Mode)
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    const applyTheme = (e) => {
      if (e.matches) {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    };
    
    // Terapkan saat pertama kali render
    applyTheme(mediaQuery);
    
    // Dengarkan perubahan jika user mengubah tema sistem
    mediaQuery.addEventListener('change', applyTheme);

    const handleScroll = () => setScrolled(window.scrollY > 20);
    window.addEventListener('scroll', handleScroll);
    
    return () => {
      window.removeEventListener('scroll', handleScroll);
      mediaQuery.removeEventListener('change', applyTheme);
    };
  }, []);

  const navigateTo = (view) => {
    setCurrentView(view);
    setIsMobileMenuOpen(false);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  return (
    <div className="min-h-screen bg-[#FAFAFA] dark:bg-[#0A0A0A] text-[#111827] dark:text-[#E2E8F0] font-sans selection:bg-[#FF6F00]/30 selection:text-[#FF6F00]">
      
      {/* Refined Global Background */}
      <div className="fixed inset-0 pointer-events-none z-0">
        <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_center,rgba(255,111,0,0.05)_0%,transparent_50%)] dark:bg-[radial-gradient(circle_at_top_center,rgba(255,111,0,0.08)_0%,transparent_50%)]"></div>
        <div className="absolute inset-0 bg-[linear-gradient(to_right,#8881_1px,transparent_1px),linear-gradient(to_bottom,#8881_1px,transparent_1px)] bg-[size:40px_40px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]"></div>
      </div>

      {/* Navigation */}
      <nav className={`fixed top-0 w-full z-50 transition-all duration-500 ${scrolled ? 'bg-white/80 dark:bg-[#0A0A0A]/80 backdrop-blur-xl border-b border-gray-200 dark:border-white/5 py-3' : 'bg-transparent border-transparent py-5'}`}>
        <div className="max-w-7xl mx-auto px-6 flex items-center justify-between">
          
          <div className="flex items-center gap-3 cursor-pointer group" onClick={() => navigateTo('landing')}>
            <div className="relative">
              <div className="absolute -inset-2 bg-orange-500/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
              <img src="/lubixlogo.svg" alt="LubiX Logo" className="relative w-9 h-9 object-contain transition-transform duration-500 group-hover:rotate-[360deg]" />
            </div>
            <span className="text-2xl md:text-3xl font-black tracking-tighter text-gray-900 dark:text-white">LubiX</span>
          </div>

          {/* Desktop Nav */}
          <div className="hidden md:flex items-center gap-10 text-[13px] font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">
            <button onClick={() => navigateTo('landing')} className="hover:text-[#FF6F00] transition-colors">Home</button>
            <button onClick={() => navigateTo('docs')} className="hover:text-[#FF6F00] transition-colors">Documentation</button>
            <a href="https://github.com/LubiXLubiX" target="_blank" rel="noreferrer" className="hover:text-[#FF6F00] transition-colors flex items-center gap-2">
              GitHub
            </a>
            
            <button 
              onClick={() => navigateTo('docs')}
              className="relative group px-6 py-2.5 rounded-full overflow-hidden"
            >
              <div className="absolute inset-0 bg-gray-900 dark:bg-white transition-transform duration-300 group-hover:scale-105"></div>
              <span className="relative text-white dark:text-gray-900 flex items-center gap-2">
                Get Started
                <ArrowRight className="w-4 h-4 transition-transform group-hover:translate-x-1" />
              </span>
            </button>
          </div>

          {/* Mobile Menu Toggle */}
          <button 
            className="md:hidden p-2 -mr-2 text-gray-600 dark:text-gray-300"
            onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
          >
            {isMobileMenuOpen ? <X /> : <Menu />}
          </button>
        </div>

        {/* Mobile Nav */}
        <div className={`md:hidden absolute top-full left-0 w-full bg-white dark:bg-[#0A0A0A] border-b border-gray-200 dark:border-white/5 shadow-xl transition-all duration-300 origin-top ${isMobileMenuOpen ? 'scale-y-100 opacity-100' : 'scale-y-0 opacity-0 pointer-events-none'}`}>
          <div className="flex flex-col p-4 gap-2">
            <button onClick={() => navigateTo('landing')} className="text-left px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 font-medium">Home</button>
            <button onClick={() => navigateTo('docs')} className="text-left px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 font-medium">Documentation</button>
            <a href="https://github.com/LubiXLubiX" className="text-left px-4 py-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 font-medium flex items-center gap-2"><Github className="w-4 h-4"/> GitHub</a>
            <button onClick={() => navigateTo('docs')} className="bg-[#FF6F00] text-white px-4 py-3 rounded-xl font-bold text-center mt-2">Get Started</button>
          </div>
        </div>
      </nav>

      {/* Main Content */}
      <div className="pt-20 relative z-10">
        {currentView === 'landing' ? <LandingView onNavigate={navigateTo} /> : <DocsView />}
      </div>

      {/* Elegant Footer */}
      <footer className="relative z-10 border-t border-gray-200 dark:border-white/5 bg-white dark:bg-[#0A0A0A] py-12 md:py-16 mt-20">
        <div className="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">
          <div className="flex items-center gap-3">
            <img src="/lubixlogo.svg" alt="LubiX Logo" className="w-6 h-6 object-contain grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all" />
            <span className="text-sm font-semibold text-gray-500 dark:text-gray-400">LubiX Framework</span>
          </div>
          <div className="flex gap-8 text-sm font-medium text-gray-500 dark:text-gray-400">
            <button onClick={() => navigateTo('docs')} className="hover:text-gray-900 dark:hover:text-white transition-colors">Documentation</button>
            <a href="https://github.com/LubiXLubiX" className="hover:text-gray-900 dark:hover:text-white transition-colors">GitHub</a>
          </div>
          <p className="text-sm text-gray-400 dark:text-gray-500">&copy; {new Date().getFullYear()} LubiX. Crafted with precision.</p>
        </div>
      </footer>
    </div>
  );
}

// ==========================================
// VIEWS
// ==========================================

function LandingView({ onNavigate }) {
  return (
    <main className="animate-in fade-in slide-in-from-bottom-4 duration-1000">
      {/* Hero Section - Laravel 12 Aesthetic */}
      <section className="max-w-7xl mx-auto px-6 pt-32 md:pt-48 pb-32 text-center relative">
        <div className="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] bg-gradient-to-b from-orange-500/5 to-transparent rounded-full blur-[120px] -z-10"></div>
        
        <h1 className="text-6xl md:text-8xl lg:text-9xl font-black tracking-tight text-gray-900 dark:text-white leading-[0.9] mb-8">
          The PHP framework <br className="hidden md:block"/>
          for <span className="text-[#FF6F00]">web artisans.</span>
        </h1>
        
        <p className="text-xl md:text-2xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto mb-12 leading-relaxed font-medium">
          LubiX is a web application framework with expressive, elegant syntax. We’ve already laid the foundation — freeing you to create without sweating the small things.
        </p>
        
        <div className="flex flex-col sm:flex-row items-center justify-center gap-6">
          <button 
            onClick={() => onNavigate('docs')}
            className="w-full sm:w-auto px-10 py-4 bg-[#FF6F00] text-white rounded-xl font-bold text-lg hover:bg-[#e66400] transition-all shadow-xl shadow-orange-500/20 flex items-center justify-center gap-2 group"
          >
            Get Started
            <ArrowRight className="w-5 h-5 transition-transform group-hover:translate-x-1" />
          </button>
          
          <div className="w-full sm:w-auto flex items-center justify-between gap-6 bg-white dark:bg-[#111] border border-gray-200 dark:border-white/10 px-8 py-4 rounded-xl font-mono text-sm shadow-sm group">
            <div className="flex items-center gap-3">
              <span className="text-gray-400">$</span>
              <span className="text-gray-900 dark:text-gray-100 font-bold">deca lubix init</span>
            </div>
            <button 
              onClick={() => copyToClipboard('deca lubix init')}
              className="text-gray-400 hover:text-[#FF6F00] transition-colors"
            >
              <Code2 className="w-5 h-5" />
            </button>
          </div>
        </div>
      </section>

      {/* Feature Grid - Laravel Style */}
      <section className="max-w-7xl mx-auto px-6 py-32 border-t border-gray-200 dark:border-white/5">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
          <div className="space-y-4">
            <div className="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center">
              <Blocks className="w-6 h-6 text-[#FF6F00]" />
            </div>
            <h3 className="text-xl font-bold text-gray-900 dark:text-white">Unified Architecture</h3>
            <p className="text-gray-600 dark:text-gray-400 leading-relaxed">
              LubiX provides an amazing developer experience while providing powerful features such as thorough dependency injection and an expressive database ORM.
            </p>
          </div>

          <div className="space-y-4">
            <div className="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center">
              <Zap className="w-6 h-6 text-[#FF6F00]" />
            </div>
            <h3 className="text-xl font-bold text-gray-900 dark:text-white">Zero Build Experience</h3>
            <p className="text-gray-600 dark:text-gray-400 leading-relaxed">
              Paired with Deca, LubiX offers a modern frontend development experience without the complexity of traditional build tools.
            </p>
          </div>

          <div className="space-y-4">
            <div className="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center">
              <Shield className="w-6 h-6 text-[#FF6F00]" />
            </div>
            <h3 className="text-xl font-bold text-gray-900 dark:text-white">Secure by Default</h3>
            <p className="text-gray-600 dark:text-gray-400 leading-relaxed">
              Built-in protection against SQL injection, cross-site request forgery, and cross-site scripting.
            </p>
          </div>
        </div>
      </section>

      {/* Code Showcase - Clean & Professional */}
      <section className="bg-gray-50 dark:bg-[#050505] py-32 border-y border-gray-200 dark:border-white/5">
        <div className="max-w-7xl mx-auto px-6">
          <div className="grid lg:grid-cols-2 gap-16 items-center">
            <div>
              <h2 className="text-4xl md:text-5xl font-black tracking-tight text-gray-900 dark:text-white mb-6">
                Write code that <span className="text-[#FF6F00]">sparks joy.</span>
              </h2>
              <p className="text-lg text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                LubiX values beauty. We believe development is most productive when the code is expressive, simple, and clean.
              </p>
              <ul className="space-y-4">
                {['Expressive routing', 'Powerful dependency injection', 'Simplified database migrations'].map((item) => (
                  <li key={item} className="flex items-center gap-3 text-gray-700 dark:text-gray-300 font-bold">
                    <CheckCircle2 className="w-5 h-5 text-green-500" />
                    {item}
                  </li>
                ))}
              </ul>
            </div>
            <div className="relative">
              <div className="absolute -inset-4 bg-orange-500/20 rounded-[2rem] blur-2xl opacity-20"></div>
              <div className="relative rounded-2xl bg-[#0A0A0A] border border-gray-800 shadow-2xl overflow-hidden font-mono text-sm leading-relaxed p-8 text-gray-300">
                <div className="flex items-center gap-2 mb-8 opacity-50">
                  <div className="w-3 h-3 rounded-full bg-red-500"></div>
                  <div className="w-3 h-3 rounded-full bg-yellow-500"></div>
                  <div className="w-3 h-3 rounded-full bg-green-500"></div>
                </div>
                <div className="space-y-1">
                  <p><span className="text-blue-400">use</span> Lubix\Core\Routing\<span className="text-yellow-400">Router</span>;</p>
                  <p><span className="text-blue-400">use</span> Lubix\Core\Http\<span className="text-yellow-400">Response</span>;</p>
                  <p className="text-gray-600 mt-4">// Define your routes</p>
                  <p><span className="text-purple-400">$router</span>-&gt;<span className="text-blue-300">get</span>(<span className="text-green-300">'/'</span>, <span className="text-blue-400">function</span> () &#123;</p>
                  <p className="pl-4">  <span className="text-blue-400">return</span> <span className="text-yellow-400">Response</span>::<span className="text-blue-300">json</span>([</p>
                  <p className="pl-8">    <span className="text-green-300">'message'</span> =&gt; <span className="text-green-300">'Welcome to the future'</span></p>
                  <p className="pl-4">  ]);</p>
                  <p>&#125;);</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Final CTA */}
      <section className="py-32 text-center">
        <div className="max-w-4xl mx-auto px-6">
          <h2 className="text-4xl md:text-6xl font-black tracking-tight text-gray-900 dark:text-white mb-8">
            Ready to start your <br/> next big project?
          </h2>
          <button 
            onClick={() => onNavigate('docs')}
            className="px-12 py-5 bg-[#FF6F00] text-white rounded-xl font-bold text-xl hover:bg-[#e66400] transition-all shadow-xl shadow-orange-500/20"
          >
            Explore Documentation
          </button>
        </div>
      </section>
    </main>
  );
}

function DocsView() {
  const [activeSection, setActiveSection] = useState('intro');

  const sections = {
    intro: {
      title: 'Introduction',
      content: (
        <div className="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
          <div className="space-y-4">
            <h2 className="text-4xl font-black tracking-tight text-gray-900 dark:text-white">Introduction</h2>
            <p className="text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
              LubiX is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. LubiX attempts to take the pain out of development by easing common tasks used in the majority of web projects.
            </p>
          </div>

          <div className="p-8 rounded-2xl bg-orange-500/5 border border-orange-500/10 space-y-4">
            <h3 className="text-xl font-bold text-[#FF6F00]">Installation</h3>
            <p className="text-gray-700 dark:text-gray-300">To get started with LubiX, you should use the Deca CLI to initialize your project. This will set up all the necessary directories and dependencies.</p>
            <div className="bg-[#0A0A0A] p-4 rounded-xl font-mono text-sm text-gray-300 border border-white/5 flex justify-between items-center group">
              <span>deca lubix init my-app</span>
              <button onClick={() => copyToClipboard('deca lubix init my-app')} className="text-gray-500 hover:text-[#FF6F00] transition-colors">
                <Code2 className="w-4 h-4" />
              </button>
            </div>
          </div>

          <div className="grid md:grid-cols-2 gap-6">
            <div className="p-6 rounded-2xl bg-white dark:bg-[#111] border border-gray-200 dark:border-white/5 space-y-3">
              <div className="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center">
                <LayoutTemplate className="w-5 h-5 text-blue-500" />
              </div>
              <h4 className="font-bold text-gray-900 dark:text-white">Modern Frontend</h4>
              <p className="text-sm text-gray-500 dark:text-gray-400">First-class React support with zero-build configuration via Deca.</p>
            </div>
            <div className="p-6 rounded-2xl bg-white dark:bg-[#111] border border-gray-200 dark:border-white/5 space-y-3">
              <div className="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center">
                <Database className="w-5 h-5 text-purple-500" />
              </div>
              <h4 className="font-bold text-gray-900 dark:text-white">Robust Backend</h4>
              <p className="text-sm text-gray-500 dark:text-gray-400">Native PHP 8.3 performance with a clean, expressive routing engine.</p>
            </div>
          </div>
        </div>
      )
    },
    routing: {
      title: 'Routing',
      content: (
        <div className="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
          <div className="space-y-4">
            <h2 className="text-4xl font-black tracking-tight text-gray-900 dark:text-white">Routing</h2>
            <p className="text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
              The most basic LubiX routes accept a URI and a closure, providing a very simple and expressive method of defining routes and behavior without complicated routing configuration files.
            </p>
          </div>

          <div className="rounded-2xl bg-[#0A0A0A] border border-gray-800 overflow-hidden shadow-2xl">
            <div className="px-6 py-4 bg-[#111] border-b border-gray-800 flex justify-between items-center">
              <span className="text-xs font-mono text-gray-500">routes/web.php</span>
              <div className="flex gap-1.5">
                <div className="w-2.5 h-2.5 rounded-full bg-red-500/20"></div>
                <div className="w-2.5 h-2.5 rounded-full bg-yellow-500/20"></div>
                <div className="w-2.5 h-2.5 rounded-full bg-green-500/20"></div>
              </div>
            </div>
            <div className="p-8 font-mono text-sm leading-relaxed text-gray-300">
              <p><span className="text-blue-400">use</span> Lubix\Core\Http\<span className="text-yellow-400">Response</span>;</p>
              <p className="mt-4"><span className="text-purple-400">$router</span>-&gt;<span className="text-blue-300">get</span>(<span className="text-green-300">'/greeting'</span>, <span className="text-blue-400">function</span> () &#123;</p>
              <p className="pl-4">  <span className="text-blue-400">return</span> <span className="text-yellow-400">Response</span>::<span className="text-blue-300">html</span>(<span className="text-green-300">'Hello World'</span>);</p>
              <p>&#125;);</p>
            </div>
          </div>

          <div className="space-y-4">
            <h3 className="text-2xl font-bold text-gray-900 dark:text-white">Route Parameters</h3>
            <p className="text-gray-600 dark:text-gray-400">Sometimes you will need to capture segments of the URI within your route. For example, you may need to capture a user's ID from the URL:</p>
            <div className="bg-[#0A0A0A] p-6 rounded-xl font-mono text-sm text-gray-400 border border-white/5">
              <p><span className="text-purple-400">$router</span>-&gt;<span className="text-blue-300">get</span>(<span className="text-green-300">'/user/&#123;id&#125;'</span>, <span className="text-blue-400">function</span> (<span className="text-purple-400">$request</span>, <span className="text-purple-400">$params</span>) &#123;</p>
              <p className="pl-4">  <span className="text-blue-400">return</span> <span className="text-green-300">"User ID: "</span> . <span className="text-purple-400">$params</span>[<span className="text-green-300">'id'</span>];</p>
              <p>&#125;);</p>
            </div>
          </div>
        </div>
      )
    },
    controllers: {
      title: 'Controllers',
      content: (
        <div className="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
          <div className="space-y-4">
            <h2 className="text-4xl font-black tracking-tight text-gray-900 dark:text-white">Controllers</h2>
            <p className="text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
              Instead of defining all of your request handling logic as closures in your route files, you may wish to organize this behavior using "controller" classes.
            </p>
          </div>

          <div className="rounded-2xl bg-[#0A0A0A] border border-gray-800 overflow-hidden shadow-2xl">
            <div className="px-6 py-4 bg-[#111] border-b border-gray-800 flex justify-between items-center">
              <span className="text-xs font-mono text-gray-500">app/Http/Controllers/UserController.php</span>
              <div className="flex gap-1.5">
                <div className="w-2.5 h-2.5 rounded-full bg-red-500/20"></div>
                <div className="w-2.5 h-2.5 rounded-full bg-yellow-500/20"></div>
                <div className="w-2.5 h-2.5 rounded-full bg-green-500/20"></div>
              </div>
            </div>
            <div className="p-8 font-mono text-sm leading-relaxed text-gray-300">
              <p><span className="text-blue-400">namespace</span> App\Http\Controllers;</p>
              <p className="mt-4"><span className="text-blue-400">class</span> <span className="text-yellow-400">UserController</span></p>
              <p>&#123;</p>
              <p className="pl-4">  <span className="text-blue-400">public function</span> <span className="text-blue-300">show</span>(<span className="text-purple-400">$request</span>, <span className="text-purple-400">$params</span>)</p>
              <p className="pl-4">  &#123;</p>
              <p className="pl-8">    <span className="text-blue-400">return</span> <span className="text-yellow-400">Response</span>::<span className="text-blue-300">json</span>([<span className="text-green-300">'id'</span> =&gt; <span className="text-purple-400">$params</span>[<span className="text-green-300">'id'</span>]]);</p>
              <p className="pl-4">  &#125;</p>
              <p>&#125;</p>
            </div>
          </div>
        </div>
      )
    }
  };

  return (
    <div className="max-w-7xl mx-auto px-6 py-12 md:py-24 animate-in fade-in duration-700">
      <div className="flex flex-col lg:flex-row gap-16">
        {/* Sidebar Nav */}
        <aside className="lg:w-64 flex-shrink-0">
          <nav className="sticky top-32 space-y-1">
            {Object.keys(sections).map((key) => (
              <button
                key={key}
                onClick={() => setActiveSection(key)}
                className={`w-full text-left px-4 py-3 rounded-xl transition-all font-bold text-sm uppercase tracking-widest ${activeSection === key ? 'bg-[#FF6F00] text-white shadow-lg shadow-orange-500/20' : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'}`}
              >
                {sections[key].title}
              </button>
            ))}
          </nav>
        </aside>

        {/* Content Area */}
        <div className="flex-1 max-w-4xl">
          {sections[activeSection].content}
        </div>
      </div>
    </div>
  );
}

// ==========================================
// REUSABLE COMPONENTS
// ==========================================

function FeatureCard({ icon, title, desc }) {
  return (
    <div className="group p-8 bg-white dark:bg-[#111111] border border-gray-200 dark:border-white/5 rounded-2xl hover:border-gray-300 dark:hover:border-white/20 transition-all duration-300">
      <div className="w-12 h-12 bg-gray-50 dark:bg-[#1A1A1A] border border-gray-200 dark:border-white/5 rounded-xl flex items-center justify-center mb-6 text-gray-600 dark:text-gray-400 group-hover:text-[#FF6F00] transition-colors">
        {icon}
      </div>
      <h3 className="text-lg font-bold mb-3 tracking-tight text-gray-900 dark:text-white">{title}</h3>
      <p className="text-gray-500 dark:text-gray-400 leading-relaxed text-sm font-medium">
        {desc}
      </p>
    </div>
  );
}

function CodeBlock({ code }) {
  return (
    <div className="relative group rounded-xl overflow-hidden bg-[#0A0A0A] border border-gray-800">
      {/* Fake macOS Titlebar for aesthetic */}
      <div className="flex items-center px-4 py-2.5 bg-[#111111] border-b border-gray-800">
        <div className="flex gap-2">
          <div className="w-2.5 h-2.5 rounded-full bg-gray-600 group-hover:bg-[#FF5F56] transition-colors"></div>
          <div className="w-2.5 h-2.5 rounded-full bg-gray-600 group-hover:bg-[#FFBD2E] transition-colors"></div>
          <div className="w-2.5 h-2.5 rounded-full bg-gray-600 group-hover:bg-[#27C93F] transition-colors"></div>
        </div>
      </div>
      <pre className="p-5 overflow-x-auto text-sm font-mono text-gray-300 leading-relaxed">
        <code>
          {code.split('\n').map((line, i) => (
            <React.Fragment key={i}>
              <span className="text-gray-200">{line}</span>
              {i < code.split('\n').length - 1 && <br />}
            </React.Fragment>
          ))}
        </code>
      </pre>
      <button 
        onClick={() => copyToClipboard(code.replace(/\\n/g, '\n'))}
        className="absolute top-14 right-4 p-2 bg-white/5 hover:bg-white/10 rounded-md opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 hover:text-white border border-white/10"
        title="Copy code"
      >
        <Code2 className="w-4 h-4" />
      </button>
    </div>
  );
}
