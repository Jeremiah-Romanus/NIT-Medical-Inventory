<style>
    .shared-footer {
        padding: 22px 28px 26px;
        background: #0f2747;
        color: rgba(255, 255, 255, 0.78);
        font-size: 0.88rem;
        display: flex;
        justify-content: space-between;
        gap: 16px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .shared-footer strong,
    .shared-footer .text-dark {
        color: #ffffff !important;
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
