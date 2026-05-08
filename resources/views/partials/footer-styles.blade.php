<style>
    .shared-footer {
        padding: 22px 28px 26px;
        background: #f3f9ff;
        color: #475569;
        font-size: 0.88rem;
        display: flex;
        justify-content: space-between;
        gap: 16px;
        border-top: 3px solid #8fd3ff;
    }

    .shared-footer strong,
    .shared-footer .text-dark {
        color: #0f172a !important;
    }

    .shared-footer.page-footer {
        margin-top: auto;
    }

    .shared-footer.standalone-footer {
        width: min(1120px, 100%);
        margin: 0 auto;
        border-radius: 16px;
    }

    .shared-footer.hero-footer {
        margin-top: 22px;
    }

    @media (max-width: 767.98px) {
        .shared-footer {
            padding: 14px 18px 20px;
            flex-direction: column;
        }
    }
</style>
