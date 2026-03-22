<style type="text/css">
    /* Shared form styling for Core Settings pages */
    #page {
        max-width: 1120px;
        margin: 0 auto;
    }

    #content {
        display: grid;
        grid-template-columns: 240px minmax(320px, 680px);
        gap: 28px;
        align-items: start;
    }

    #content .post {
        grid-column: 1 / -1;
        margin-bottom: 2px;
    }

    #content .post h4 {
        margin: 0;
        font-size: 30px;
        line-height: 1.1;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: #0f172a;
    }

    #content .post .section-kicker {
        display: inline-block;
        margin-bottom: 10px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        line-height: 1;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #1e3a8a;
        background: #dbeafe;
        border: 1px solid #bfdbfe;
    }

    #content_left {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
        min-height: 120px;
    }

    #content_left .navigation,
    #content_left .navigation_current {
        margin-bottom: 10px;
    }

    #content_left .navigation:last-child,
    #content_left .navigation_current:last-child {
        margin-bottom: 0;
    }

    #content_left .navigation a,
    #content_left .navigation_current a {
        display: block;
        padding: 10px 12px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        transition: all .2s ease;
    }

    #content_left .navigation a {
        color: #334155;
        background: #f8fafc;
    }

    #content_left .navigation a:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    #content_left .navigation_current a {
        color: #ffffff;
        background: linear-gradient(135deg, #1e293b, #1d4ed8);
    }

    #content_right .comteeMem {
        background: #ffffff;
        padding: 26px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.07);
    }

    #content_right .comteeMemRow {
        margin-bottom: 10px;
    }

    #content_right .form_alert {
        margin-bottom: 14px;
        padding: 12px 14px;
        border-radius: 10px;
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #b91c1c;
        font-weight: 600;
        font-size: 14px;
    }

    /* Listing/table-like blocks used in manage pages */
    #content_right .committeeTitle,
    #content_right .subjHeader,
    #content_right .usersDetHeader {
        display: grid;
        grid-template-columns: minmax(160px, 1fr) minmax(220px, 1.4fr) minmax(180px, 1fr);
        gap: 14px;
        align-items: center;
        padding: 12px 14px;
        border-radius: 12px;
    }

    #content_right .committeeTitle {
        background: #eef2ff;
        border: 1px solid #c7d2fe;
        color: #1e3a8a;
        font-weight: 700;
        margin-bottom: 10px;
    }

    #content_right .subjHeader,
    #content_right .usersDetHeader {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #1e293b;
    }

    #content_right .subjName,
    #content_right .subjMaterials,
    #content_right .eventCandName {
        font-size: 15px;
        line-height: 1.4;
        word-break: break-word;
    }

    #content_right .subjMaterials .eventCandName {
        margin-bottom: 8px;
    }

    #content_right .subjMaterials .eventCandName:last-child {
        margin-bottom: 0;
    }

    #content_right .eventCandName a {
        text-decoration: none;
    }

    #content_right .eventCandName input.button,
    #content_right .subjMaterials input.button {
        padding: 8px 14px;
        border: 0;
        border-radius: 9px;
        background: linear-gradient(135deg, #0f172a, #1e3a8a);
        color: #ffffff;
        font-size: 14px;
        font-weight: 600;
        min-height: 38px;
        cursor: pointer;
        box-shadow: 0 6px 12px rgba(30, 58, 138, 0.16);
        transition: transform .2s ease, filter .2s ease, box-shadow .2s ease;
    }

    #content_right .eventCandName input.button:hover,
    #content_right .subjMaterials input.button:hover {
        filter: brightness(1.06);
        transform: translateY(-1px);
        box-shadow: 0 10px 20px rgba(30, 58, 138, 0.2);
    }

    /* Bottom add button block */
    #content_right > .eventCandName {
        margin-top: 14px;
    }

    #content_right > .eventCandName input.button {
        min-height: 46px;
        padding: 10px 20px;
        font-size: 16px;
    }

    #content_right .comteeMemRow .usersDetHeader {
        color: #b91c1c;
        font-weight: 600;
    }

    #content_right form .form_row {
        margin-bottom: 16px;
    }

    #content_right form .form_hint {
        margin-top: 7px;
        display: block;
        color: #64748b;
        font-size: 13px;
        line-height: 1.35;
    }

    #content_right form .form_label {
        margin-bottom: 6px;
    }

    #content_right form .form_label label {
        font-size: 17px;
        font-weight: 700;
        color: #1e293b;
    }

    #content_right form .form_field input[type="text"],
    #content_right form .form_field input[type="file"],
    #content_right form .form_field select,
    #content_right form .form_field textarea {
        width: 100%;
        min-height: 52px;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        background: #f8fafc;
        font-size: 16px;
        outline: none;
        transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
    }

    #content_right form .form_field input[type="file"]::file-selector-button {
        margin-right: 12px;
        border: 1px solid #94a3b8;
        background: #ffffff;
        color: #0f172a;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color .2s ease, border-color .2s ease;
    }

    #content_right form .form_field input[type="file"]::file-selector-button:hover {
        background: #f1f5f9;
        border-color: #64748b;
    }

    #content_right form .form_field textarea {
        min-height: 120px;
        resize: vertical;
    }

    #content_right form .form_field input[type="text"]:focus,
    #content_right form .form_field input[type="file"]:focus,
    #content_right form .form_field select:focus,
    #content_right form .form_field textarea:focus {
        border-color: #2563eb;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.14);
    }

    #content_right form .form_field input[type="submit"],
    #content_right form .form_field .button {
        padding: 12px 24px;
        border: 0;
        border-radius: 12px;
        background: linear-gradient(135deg, #0f172a, #1e3a8a);
        color: #ffffff;
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        transition: transform .2s ease, filter .2s ease, box-shadow .2s ease;
        box-shadow: 0 8px 16px rgba(30, 58, 138, 0.2);
        width: auto;
        min-height: 48px;
    }

    #content_right form .form_field input[type="submit"]:hover,
    #content_right form .form_field .button:hover {
        filter: brightness(1.06);
        transform: translateY(-1px);
        box-shadow: 0 12px 24px rgba(30, 58, 138, 0.25);
    }

    #content_right form .form_field input[type="submit"]:active,
    #content_right form .form_field .button:active {
        transform: translateY(0);
    }

    #content.single-panel-layout {
        grid-template-columns: minmax(320px, 760px);
        justify-content: start;
    }

    #content.single-panel-layout #content_right {
        grid-column: 1;
    }

    #content.single-panel-layout #content_right .comteeMem {
        max-width: 740px;
        padding: 30px;
    }

    #content.single-panel-layout #content_right form .form_actions {
        margin-top: 6px;
    }

    #addSyllabus.core-form {
        display: grid;
        gap: 16px;
    }

    #addSyllabus.core-form .form_row {
        display: grid;
        grid-template-columns: 180px minmax(0, 1fr);
        gap: 14px 22px;
        margin: 0;
        align-items: center;
    }

    #addSyllabus.core-form .form_label {
        margin: 0;
        display: flex;
        align-items: center;
        min-height: 52px;
    }

    #addSyllabus.core-form .form_label label {
        margin: 0;
    }

    #addSyllabus.core-form .form_field {
        min-width: 0;
    }

    #addSyllabus.core-form .field_shell {
        border: 1px solid #d7e0ec;
        border-radius: 14px;
        background: linear-gradient(180deg, #f8fafc, #f1f5f9);
        padding: 3px;
    }

    #addSyllabus.core-form .form_row--file .form_label {
        align-items: flex-start;
        padding-top: 11px;
    }

    #addSyllabus.core-form .form_row--file .field_shell {
        padding: 8px 12px;
        min-height: 56px;
        display: flex;
        align-items: center;
    }

    #addSyllabus.core-form .form_row--class .field_shell select {
        min-height: 52px;
        border: 0;
        background: transparent;
        box-shadow: none;
    }

    #addSyllabus.core-form .form_row--file .field_shell input[type="file"] {
        min-height: auto;
        padding: 0;
        border: 0;
        border-radius: 0;
        background: transparent;
        box-shadow: none;
        font-size: 15px;
    }

    #addSyllabus.core-form .field_shell:focus-within {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.14);
        background: #ffffff;
    }

    #addSyllabus.core-form .form_hint {
        margin-top: 8px;
        padding-left: 1px;
    }

    #addSyllabus.core-form .form_actions .form_label {
        min-height: 0;
    }

    #addSyllabus.core-form .form_actions .button {
        min-width: 190px;
    }

    @media (max-width: 980px) {
        #content {
            grid-template-columns: 1fr;
            gap: 18px;
        }

        #content.single-panel-layout {
            grid-template-columns: 1fr;
        }

        #content.single-panel-layout #content_right .comteeMem {
            max-width: 100%;
        }

        #content .post h4 {
            font-size: 26px;
        }

        #content .post .section-kicker {
            margin-bottom: 8px;
        }

        #content_right form .form_label label {
            font-size: 16px;
        }

        #content_right form .form_field input[type="submit"],
        #content_right form .form_field .button {
            font-size: 17px;
        }

        #content_right .committeeTitle,
        #content_right .subjHeader,
        #content_right .usersDetHeader {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        #addSyllabus.core-form .form_row {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        #addSyllabus.core-form .form_label {
            min-height: 0;
            display: block;
        }

        #addSyllabus.core-form .form_row--file .form_label {
            padding-top: 0;
        }

        #addSyllabus.core-form .form_actions .button {
            width: 100%;
        }
    }

    @media (max-width: 767px) {
        #page {
            width: 100%;
        }

        #content {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        #content .post h4 {
            font-size: 24px;
        }

        #content_left,
        #content_right .comteeMem,
        #content.single-panel-layout #content_right .comteeMem {
            padding: 16px;
            border-radius: 16px;
        }

        #content_right .committeeTitle,
        #content_right .subjHeader,
        #content_right .usersDetHeader {
            grid-template-columns: 1fr;
            gap: 10px;
            padding: 12px;
        }

        #content_right .eventCandName,
        #content_right .subjMaterials,
        #content_right .subjName {
            width: 100%;
        }

        #content_right .eventCandName input.button,
        #content_right .subjMaterials input.button,
        #content_right form .form_field input[type="submit"],
        #content_right form .form_field .button,
        #content_right > .eventCandName input.button {
            width: 100%;
            justify-content: center;
            font-size: 15px;
        }

        #content_right form .form_label label {
            font-size: 15px;
        }

        #content_right form .form_field input[type="text"],
        #content_right form .form_field input[type="file"],
        #content_right form .form_field select,
        #content_right form .form_field textarea {
            min-height: 48px;
            font-size: 15px;
        }

        #addSyllabus.core-form .form_row {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        #addSyllabus.core-form .form_label,
        #addSyllabus.core-form .form_row--file .form_label {
            min-height: 0;
            padding-top: 0;
            align-items: flex-start;
        }
    }

    @media (max-width: 575px) {
        #content .post .section-kicker {
            font-size: 11px;
            padding: 5px 10px;
        }

        #content_right .comteeMem,
        #content.single-panel-layout #content_right .comteeMem,
        #content_left {
            padding: 14px;
        }

        #content_right form .form_row {
            margin-bottom: 14px;
        }

        #content_right form .form_field input[type="file"]::file-selector-button {
            width: 100%;
            margin-right: 0;
            margin-bottom: 8px;
        }
    }

</style>
