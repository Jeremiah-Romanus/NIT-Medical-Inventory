<style>
    .shared-site-header {
        width: 100%;
        position: sticky;
        top: 0;
        z-index: 1100;
        background: rgba(255, 255, 255, 0.96);
        border-bottom: 1px solid rgba(143, 211, 255, 0.28);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.03);
        backdrop-filter: blur(12px);
    }

    .shared-site-header-inner {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: 14px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
    }

    .shared-site-brand {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
        margin-left: 0;
    }

    .shared-site-brand img {
        width: 42px;
        height: 42px;
        object-fit: contain;
        flex: 0 0 auto;
    }

    .shared-site-brand strong {
        display: block;
        font-size: 0.95rem;
        line-height: 1.2;
        color: #0f172a;
    }

    .shared-site-brand span {
        display: block;
        color: #64748b;
        font-size: 0.82rem;
        line-height: 1.35;
    }

    .shared-site-nav {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
        margin-right: 6px;
    }

    .mobile-nav-toggle {
        display: none;
        width: 52px;
        height: 52px;
        border-radius: 16px;
        border: 1px solid rgba(143, 211, 255, 0.32);
        background: rgba(255, 255, 255, 0.96);
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 5px;
        padding: 0;
    }

    .mobile-nav-toggle span {
        display: block;
        width: 20px;
        height: 2px;
        border-radius: 999px;
        background: #1692de;
    }

    .shared-site-nav a {
        text-decoration: none;
        color: #0f172a;
        font-size: 1rem;
        font-weight: 600;
        padding: 10px 14px;
        border-radius: 999px;
        border: 1px solid rgba(143, 211, 255, 0.32);
        background: rgba(255, 255, 255, 0.92);
    }

    @media (max-width: 991.98px) {
        .shared-site-header-inner {
            padding: 14px 18px;
            position: relative;
        }

        .mobile-nav-toggle {
            display: inline-flex;
        }

        .shared-site-nav {
            position: absolute;
            top: calc(100% + 10px);
            right: 18px;
            min-width: 180px;
            display: none;
            padding: 12px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.98);
            border: 1px solid rgba(143, 211, 255, 0.32);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
            z-index: 1200;
        }

        .shared-site-header.nav-open .shared-site-nav {
            display: flex;
        }

        .shared-site-nav a {
            text-align: center;
        }
    }
</style>
