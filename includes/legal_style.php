<?php include 'includes/header.php'; ?>
<style>
.legal-page { background: #f5f6f8; min-height: 100vh; }

/* Hero */
.legal-hero {
    background: linear-gradient(135deg, #0d2137 0%, #1a3c5e 60%, #00b6bd 100%);
    padding: 80px 8% 60px;
    text-align: center;
    color: #fff;
}
.legal-hero-icon {
    width: 70px; height: 70px;
    background: rgba(255,255,255,0.15);
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.9rem;
    margin-bottom: 20px;
    backdrop-filter: blur(10px);
}
.legal-hero h1 {
    font-size: 4.5rem;
    font-weight: 800;
    margin-bottom: 10px;
    letter-spacing: -0.5px;
}
.legal-hero p {
    font-size: 1.2rem;
    opacity: 0.8;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
    text-transform: none;
}

/* Container */
.legal-container { padding: 50px 8%; max-width: 1000px; margin: 0 auto; }

/* Card */
.legal-card {
    background: #fff;
    border-radius: 16px;
    padding: 36px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    margin-bottom: 24px;
}

/* Section */
.legal-section {
    display: flex;
    gap: 20px;
    padding: 24px 0;
    border-bottom: 1px solid #eef1f5;
}
.legal-section:last-child { border-bottom: none; padding-bottom: 0; }
.legal-section:first-child { padding-top: 0; }

.legal-icon {
    width: 42px; height: 42px;
    background: #e8f9f9;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: #00b6bd;
    font-size: 2rem;
    flex-shrink: 0;
    margin-top: 3px;
}

.legal-content h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a3c5e;
    margin-bottom: 8px;
}
.legal-content p {
    font-size: 1.4rem;
    color: #666;
    line-height: 1.7;
    white-space: pre-line;
    text-transform: none;
}

/* Patient Rights Grid */
.rights-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}
.rights-card {
    background: #fff;
    border-radius: 14px;
    padding: 28px 22px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    border-top: 3px solid #00b6bd;
    transition: transform 0.25s ease;
}
.rights-card:hover { transform: translateY(-6px); }
.rights-num {
    font-size: 3.1rem;
    font-weight: 800;
    color: #e8f9f9;
    margin-bottom: 10px;
    line-height: 1;
}
.rights-card h3 {
    font-size: 1.9rem;
    font-weight: 700;
    color: #1a3c5e;
    margin-bottom: 8px;
}
.rights-card p {
    font-size: 1.4rem;
    color: #777;
    line-height: 1.6;
    text-transform: none;
}

/* Careers */
.careers-why {
    background: #fff;
    border-radius: 16px;
    padding: 32px;
    display: flex;
    gap: 20px;
    align-items: flex-start;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    margin-bottom: 24px;
    border-left: 4px solid #00b6bd;
}
.careers-why-icon {
    width: 48px; height: 48px;
    background: #e8f9f9;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: #00b6bd; font-size: 1.7rem; flex-shrink: 0;
}
.careers-why h2 {
    font-size: 2.8rem; font-weight: 700; color: #1a3c5e; margin-bottom: 8px;
}
.careers-why p { font-size: 1.3rem; color: #666; line-height: 1.7; text-transform: none; }

.careers-perks {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 24px;
    justify-content: center;
}
.perk-item {
    background: #fff;
    border-radius: 12px;
    padding: 18px 24px;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    font-size: 1.3rem;
    font-weight: 600;
    color: #1a3c5e;
    transition: transform 0.2s;
}
.perk-item:hover { transform: translateY(-4px); }
.perk-item i { color: #00b6bd; font-size: 1.9rem; }

.careers-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 16px;
    padding: 12px 24px;
    background: #00b6bd;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.3rem;
    transition: background 0.2s;
}
.careers-btn:hover { background: #1a3c5e; }

/* Org Chart */
.org-tree { text-align: center; padding: 20px 0; }
.org-level {
    display: flex;
    justify-content: center;
    gap: 16px;
    flex-wrap: wrap;
}
.org-departments { gap: 12px; }
.org-connector {
    width: 2px;
    height: 40px;
    background: #00b6bd;
    margin: 0 auto;
}
.org-box {
    background: #fff;
    border-radius: 12px;
    padding: 20px 24px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.07);
    border-top: 3px solid #00b6bd;
    min-width: 130px;
    transition: transform 0.2s;
}
.org-box:hover { transform: translateY(-4px); }
.org-box i { color: #00b6bd; font-size: 1.7rem; margin-bottom: 8px; display: block; }
.org-box h3 { font-size: 1.8rem; font-weight: 700; color: #1a3c5e; }
.org-box p  { font-size: 1.3rem; color: #888; margin-top: 4px;  text-transform: none;}
.org-top, .org-president { min-width: 200px; }
.org-top { border-top-color: #f4a40a; }
.org-top i { color: #f4a40a; }

/* Responsive */
@media (max-width: 768px) {
    .legal-hero h1 { font-size: 1.8rem; }
    .legal-container { padding: 30px 5%; }
    .legal-card { padding: 24px; }
    .legal-section { flex-direction: column; gap: 12px; }
    .rights-grid { grid-template-columns: 1fr; }
    .careers-why { flex-direction: column; }
    .org-departments { flex-direction: column; align-items: center; }
}
</style>