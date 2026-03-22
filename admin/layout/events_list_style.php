<style type="text/css">
    #content_right #eventDetails {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.07);
        overflow: hidden;
    }

    #content_right .eventHeader {
        display: flex;
        gap: 10px;
        margin-bottom: 14px;
    }

    #content_right .eventCateg,
    #content_right .eventCategCurrent {
        border-radius: 10px;
    }

    #content_right .eventCateg a,
    #content_right .eventCategCurrent a {
        display: block;
        padding: 10px 14px;
        text-decoration: none;
        font-weight: 700;
        border-radius: 10px;
    }

    #content_right .eventCateg a {
        background: #e2e8f0;
        color: #334155;
    }

    #content_right .eventCategCurrent a {
        background: linear-gradient(135deg, #0f172a, #1e3a8a);
        color: #ffffff;
    }

    #content_right .eventDetHeader,
    #content_right .eventDet {
        display: grid;
        grid-template-columns: 70px 1.2fr 130px 1.6fr;
        gap: 10px;
        align-items: center;
        padding: 12px 14px;
    }

    #content_right .eventDetHeader {
        background: #eef2ff;
        color: #1e3a8a;
        font-weight: 700;
        border-bottom: 1px solid #dbe5fb;
    }

    /* Legacy markup has a leading checkbox column that is empty on this page */
    #content_right .eventDetHeader .checkBox,
    #content_right .eventDet .checkBox {
        display: none;
    }

    #content_right .eventDet {
        border-bottom: 1px solid #e5e7eb;
    }

    #content_right .eventDet:last-child {
        border-bottom: 0;
    }

    #content_right .eventRegisDates .button,
    #content_right .comteeMemDetails .button {
        border: 0;
        border-radius: 10px;
        padding: 8px 14px;
        margin-left: 6px;
        background: linear-gradient(135deg, #0f172a, #1e3a8a);
        color: #fff;
        font-weight: 700;
        box-shadow: 0 8px 16px rgba(30, 58, 138, 0.2);
    }

    #content_right .eventName a {
        color: #0f172a;
        font-weight: 600;
        text-decoration: none;
    }

    #content_right .eventName a:hover {
        color: #1d4ed8;
    }

    #content_right .event-actions-wrap {
        margin-top: 14px;
    }

    #content_right .event-add-btn {
        display: inline-block;
        border-radius: 12px;
        padding: 11px 18px;
        text-decoration: none;
        background: linear-gradient(135deg, #0f172a, #1e3a8a);
        color: #fff;
        font-weight: 700;
        box-shadow: 0 8px 16px rgba(30, 58, 138, 0.2);
    }

    #content_right .event-add-btn:hover {
        color: #fff;
        filter: brightness(1.06);
    }

    @media (max-width: 980px) {
        #content_right .eventHeader {
            flex-direction: column;
        }

        #content_right .eventDetHeader,
        #content_right .eventDet {
            grid-template-columns: 1fr;
            gap: 6px;
        }
    }

    @media (max-width: 767px) {
        #content_right #eventDetails {
            border-radius: 16px;
        }

        #content_right .eventDetHeader {
            display: none;
        }

        #content_right .eventDet {
            grid-template-columns: 1fr;
            gap: 8px;
            padding: 12px;
            border: 1px solid #d9e3ef;
            border-radius: 14px;
            margin: 0 10px 10px;
            background: #fbfdff;
        }

        #content_right .eventDet:last-child {
            margin-bottom: 10px;
        }

        #content_right .eventDet > div {
            width: 100%;
            overflow-wrap: anywhere;
        }

        #content_right .eventRegisDates .button,
        #content_right .comteeMemDetails .button,
        #content_right .event-add-btn {
            width: 100%;
            text-align: center;
            margin-left: 0;
        }
    }

    @media (max-width: 575px) {
        #content_right .eventHeader {
            gap: 8px;
        }

        #content_right .eventCateg a,
        #content_right .eventCategCurrent a {
            text-align: center;
        }
    }

</style>
