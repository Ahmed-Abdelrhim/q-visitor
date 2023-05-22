<style>
    .loading {
        z-index: 20;
        position: absolute;
        top: 0;
        left: 5px;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0, 4);
    }

    .loading-content {
        position: absolute;
        border: 16px solid #f3f3f3;
        border-top: 16px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        top: 40%;
        left: 50%;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .direction {
        direction: rtl !important;
    }

    img.logo {
        max-width: 50% !important;
        padding-bottom: 43%;
        padding-top: 8%;
    }

    .main-sidebar .sidebar-menu li.active a {
        color: #24ba64 !important;
    }

    .breadcrumb-item a {
        color: #353b99 !important;
    }

    .breadcrumb-item a {
        font-weight: 800 !important;
    }

    .breadcrumb-item.active {
        font-weight: 800;
        color: #34395e;
    }

    .navbar-bg {
        background-color: #e6e7e8 !important;
    }

    .navbar .nav-link.nav-link-lg i {
        color: #353b99 !important;
    }

    .navbar .nav-link.nav-link-user {
        color: #414141 !important;
    }

    .navbar .nav-link.nav-link-user:hover {
        color: #5b5a5a !important;
    }

    .navbar .nav-link.nav-link-user img {
        width: 30px;
        height: 30px;
        margin-right: 6px !important;
    }

    .navbar .nav-link {
        padding-right: 8px !important;
    }

    .card-icon.bg-danger {
        background-color: #24ba64 !important;
        background-image: linear-gradient(to bottom right, #24ba64, #c6cdc6) !important;
        border-radius: 25px !important;
    }

    .card-icon.bg-primary {
        background-color: #ffa426 !important;
        background-image: linear-gradient(to bottom right, #ffa426, #c6cdc6) !important;
        border-radius: 25px !important;
    }

    .card-icon.bg-warning {
        background-color: #353b99 !important;
        background-image: linear-gradient(to bottom right, #353b99, #c6cdc6) !important;
        border-radius: 25px !important;
    }

    /* .card .card-header h4{
     color: #24ba64 !important;
    } */
    .card-header .visitors {
        color: #24ba64 !important;
    }

    .badge.badge-primary {
        background-color: #24ba64 !important;
    }

    table th, table td {
        color: #34395e !important;
    }

    .bg-maroon-light {
        background-color: #353b99 !important;
    }

    .card-statistic-1 {
        border-radius: 10px;
    }

    .card .card-header h4 {
        color: #353b99 !important;
    }

    .btn-primary {
        box-shadow: 0 2px 6px #24ba64 !important;
        background-color: #24ba64 !important;
        border-color: #24ba64 !important;
    }

    .btn-primary:hover {
        box-shadow: 0 2px 6px #53db8d !important;
        background-color: #53db8d !important;
        border-color: #53db8d !important;
    }

    table td a.show {
        box-shadow: 0 2px 6px #5c5c5e !important;
        background-color: #5c5c5e !important;
        border-color: #5c5c5e !important;
    }

    table td a.show:hover {
        box-shadow: 0 2px 6px #ababab !important;
        background-color: #ababab !important;
        border-color: #ababab !important;
    }

    .page-item.active .page-link {
        background-color: #353b99 !important;
        border-color: #353b99 !important;
    }

    table td a.accept {
        box-shadow: 0 2px 6px #e79e28 !important;
        background-color: #e79e28 !important;
        border-color: #e79e28 !important;
        color: #fff;
    }

    table td a.accept:hover {
        box-shadow: 0 2px 6px #e7b059 !important;
        background-color: #e7b059 !important;
        border-color: #e7b059 !important;
        color: #fff;
    }

    a.flags {
        padding: 6px 3px !important;
    }

    .menue-flags {
        padding: 0px 8px !important;
    }

    img.flag-icon {
        margin-right: 5px;
        width: 25px;
        height: 25px;
    }

    .list-group-item.active {
        background-color: #24ba64 !important;
        border-color: #24ba64 !important;
    }

    .setting-legend {
        color: #353b99 !important;
    }

    a.text-danger {
        color: #353b99 !important;

    }

    a.dropdown-item {
        font-weight: bold !important;
    }

    a.text-danger:hover {
        color: #353b99 !important;
    }

    .scan-visit {
        width: 500px;
    }

    @media (max-width: 768px) {
        .scan-visit {
            width: 300px;
        }
    }

    @media (max-width: 350px) {
        .scan-visit {
            width: 250px;
        }
    }


</style>