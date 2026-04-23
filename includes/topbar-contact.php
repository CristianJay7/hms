<!-- ══════════════════════════════════════════
   CONTACT TOPBAR  (Version A)
   Drop this include ABOVE header.php on any page
   ══════════════════════════════════════════ -->
   <div id="contact-topbar">
    <div class="ctb-inner">

        <!-- Left: address -->
        <span class="ctb-item ctb-address">
            <i class="fa-solid fa-location-dot"></i>
            Dr. Evangelista St., Zamboanga City, Philippines
        </span>

        <!-- Divider -->
        <span class="ctb-sep">|</span>

        <!-- Middle: phone -->
        <a class="ctb-item" href="tel:+639123456789">
            <i class="fa-solid fa-phone"></i>
            +63 (62) 991-1234
        </a>

        <!-- Divider -->
        <span class="ctb-sep">|</span>

        <!-- Right: email -->
        <a class="ctb-item" href="mailto:info@zambodoctors.com">
            <i class="fa-solid fa-envelope"></i>
            info@zambodoctors.com
        </a>

        <!-- Far right: social icons -->
        <div class="ctb-socials">
            <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
        </div>

    </div>
</div>

<style>
#contact-topbar {
    width: 100%;
    background: #1a3c5e;
    color: rgba(255,255,255,0.80);
    font-size: 0.78rem;
    font-family: 'Segoe UI', sans-serif;
    z-index: 9999;
    border-bottom: 2px solid #00b6bd;
}

.ctb-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 7px 24px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.ctb-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: rgba(255,255,255,0.78);
    text-decoration: none;
    white-space: nowrap;
    transition: color 0.2s;
}
.ctb-item i { color: #00b6bd; font-size: 0.75rem; }
.ctb-item:hover { color: #fff; }

.ctb-sep {
    color: rgba(255,255,255,0.2);
    user-select: none;
}

/* Push socials to the far right */
.ctb-socials {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 12px;
}
.ctb-socials a {
    color: rgba(255,255,255,0.5);
    font-size: 0.8rem;
    transition: color 0.2s;
    text-decoration: none;
}
.ctb-socials a:hover { color: #00b6bd; }

/* Mobile: stack or shrink */
@media (max-width: 680px) {
    .ctb-inner {
        justify-content: center;
        gap: 8px;
        padding: 7px 16px;
    }
    .ctb-address { display: none; } /* hide address on small screens */
    .ctb-sep:first-of-type { display: none; }
    .ctb-socials { margin-left: 0; }
}
</style>