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
