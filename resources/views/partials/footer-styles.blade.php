<style>
    .shared-footer {
        padding: 22px 28px 26px;
        background: #09074e;
        color: #ffffff;
        font-size: 0.88rem;
        display: flex;
        justify-content: space-between;
        gap: 16px;
        border-top: 3px solid #d15400;
    }

    .shared-footer strong,
    .shared-footer .text-dark {
        color: #82a2ec !important;
    }

    .shared-footer.page-footer {
        margin-top: auto;
    }

    .shared-footer.standalone-footer {
        width: 100%;
        margin: 0;
        border-radius: 0;
    }

    .shared-footer.hero-footer {
        margin-top: 0;
    }

    .dashboard-footer-shell {
        width: 100%;
        margin-left: 280px;
    }

    .shared-footer.dashboard-footer {
        width: 100%;
        margin: 0;
        border-radius: 0;
    }

    @media (max-width: 991.98px) {
        .dashboard-footer-shell {
            margin-left: 0;
        }
    }

    @media (max-width: 767.98px) {
        .dashboard-footer-shell {
            margin-left: 0;
        }

        .shared-footer {
            padding: 14px 18px 20px;
            flex-direction: column;
            align-items: flex-start;
        }

        .shared-footer.dashboard-footer,
        .shared-footer.standalone-footer,
        .shared-footer.hero-footer,
        .shared-footer.page-footer {
            width: 100%;
        }
    }
</style>
