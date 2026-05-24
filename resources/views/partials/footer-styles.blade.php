<style>
    .shared-footer {
        padding: 18px 28px 20px;
        background: #09074e;
        color: #ffffff;
        font-size: 1rem;
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;
        gap: 20px;
        border-top: 3px solid #d15400;
    }

    .shared-footer strong,
    .shared-footer .text-dark {
        color: #82a2ec !important;
    }

    .shared-footer p {
        margin: 0;
    }

    .shared-footer-details {
        text-align: right;
        padding-left: 16px;
    }

    .shared-footer-copyright {
        text-align: center;
        padding: 0 12px;
    }

    .shared-footer-spacer {
        min-height: 1px;
    }

    .shared-footer.page-footer {
        margin-top: auto;
    }

    .shared-footer.standalone-footer {
        width: 100%;
        margin: 0;
        border-radius: 0;
    }

    .welcome-footer {
        width: 100%;
        margin-top: auto;
    }

    .shared-footer.hero-footer {
        margin-top: 0;
    }

    .dashboard-footer-shell {
        width: 100%;
        margin-left: 0;
        display: flex;
    }

    .shared-footer.dashboard-footer {
        width: 100%;
        margin: 0;
        border-radius: 0;
    }

    @media (max-width: 767.98px) {
        .shared-footer {
            padding: 14px 18px 20px;
            grid-template-columns: 1fr;
            text-align: center;
        }

        .shared-footer.dashboard-footer,
        .shared-footer.standalone-footer,
        .shared-footer.hero-footer,
        .shared-footer.page-footer {
            width: 100%;
        }

        .shared-footer-spacer {
            display: none;
        }

        .shared-footer-details,
        .shared-footer-copyright {
            text-align: center;
            padding: 0;
        }
    }
</style>
