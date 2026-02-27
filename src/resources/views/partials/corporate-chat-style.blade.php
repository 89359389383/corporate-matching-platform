<style>
    :root {
        --bg: #f6f8fb;
        --surface: #ffffff;
        --surface-2: #fbfcfe;
        --text: #0f172a;
        --muted: #64748b;
        --border: #e6eaf2;
        --border-2: #dbe2ee;
        --primary: #0366d6;
        --primary-2: #0256cc;
        --shadow-sm: 0 1px 2px rgba(15, 23, 42, 0.06);
        --shadow-md: 0 10px 30px rgba(15, 23, 42, 0.10);
        --radius-lg: 18px;
        --radius-md: 14px;
        --radius-sm: 12px;
        --focus: 0 0 0 4px rgba(3, 102, 214, 0.14);
    }
    .panel {
        background-color: var(--surface);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
        overflow: hidden;
    }
    .chat-pane {
        display: flex;
        flex-direction: column;
        min-height: min(800px, calc(100vh - var(--header-height) - 8rem));
    }
    .chat-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        background: linear-gradient(180deg, var(--surface) 0%, var(--surface-2) 100%);
    }
    .chat-title {
        display: grid;
        gap: 0.2rem;
        min-width: 0;
    }
    .chat-title strong {
        font-size: 1.25rem;
        font-weight: 900;
        color: var(--text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        letter-spacing: -0.02em;
    }
    .chat-title span {
        color: var(--muted);
        font-size: 0.9rem;
        font-weight: 700;
    }
    .chat-header .btn {
        padding: 0.7rem 1rem;
        border-radius: 10px;
        font-weight: 800;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
        cursor: pointer;
        border: 1px solid #e1e4e8;
        background: #fafbfc;
        color: #24292e;
        font-size: 0.9rem;
        white-space: nowrap;
    }
    .chat-header .btn:hover { background: #f6f8fa; transform: translateY(-1px); }
    .messages {
        padding: 0.75rem 1rem 1rem 1.5rem;
        overflow-y: auto;
        display: grid;
        gap: 0.85rem;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        position: relative;
        scrollbar-gutter: stable;
        flex: 1 1 auto;
    }
    .bubble-row { display: flex; align-items: flex-end; gap: 0.75rem; }
    .bubble-row.me { justify-content: flex-end; }
    .bubble-row.is-first,
    .bubble-row.first-message { justify-content: flex-end; width: 100%; margin-left: auto; }
    .bubble.first-message {
        max-width: 74%;
        width: 80%;
        padding: 1rem 1.25rem;
        margin-top: 0.75rem;
        border-radius: 16px;
        border-color: #cfe4ff;
        background: linear-gradient(180deg, rgba(241,248,255,0.98) 0%, rgba(236,246,255,0.98) 100%);
        box-shadow: var(--shadow-sm);
    }
    .bubble {
        max-width: 74%;
        width: 80%;
        padding: 0.9rem 1rem;
        border-radius: 16px;
        border: 1px solid var(--border);
        background: rgba(255,255,255,0.92);
        box-shadow: var(--shadow-sm);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        overflow-wrap: anywhere;
    }
    .bubble.me {
        background: linear-gradient(180deg, rgba(241,248,255,0.98) 0%, rgba(236,246,255,0.98) 100%);
        border-color: #cfe4ff;
        width: 80%;
        padding: 1rem 1.25rem;
    }
    .bubble p { color: var(--text); font-size: 0.95rem; line-height: 1.7; white-space: pre-wrap; }
    .bubble small {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-top: 0.45rem;
        color: var(--muted);
        font-weight: 800;
        font-size: 0.82rem;
    }
    .composer {
        padding: 1rem 1.25rem 1.25rem;
        border-top: 1px solid var(--border);
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.75rem;
        background: linear-gradient(180deg, var(--surface-2) 0%, var(--surface) 100%);
    }
    .composer .input {
        width: 100%;
        padding: 0.95rem 1rem;
        border: 1px solid var(--border-2);
        border-radius: 14px;
        font-size: 0.98rem;
        transition: all 0.15s ease;
        background-color: #ffffff;
        min-height: 7.25rem;
        max-height: 18rem;
        resize: vertical;
        line-height: 1.7;
        box-shadow: inset 0 1px 0 rgba(15, 23, 42, 0.03);
    }
    .composer .input:focus {
        outline: none;
        border-color: rgba(3, 102, 214, 0.55);
        box-shadow: var(--focus);
    }
    .composer .input.is-invalid {
        border-color: rgba(239, 68, 68, 0.8);
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.10);
    }
    .error-message {
        display: block;
        margin-top: 6px;
        font-size: 13px;
        font-weight: 800;
        color: #dc2626;
    }
    .composer .send {
        padding: 0.875rem 2rem;
        border-radius: 14px;
        font-weight: 900;
        border: none;
        background: linear-gradient(180deg, var(--primary) 0%, var(--primary-2) 100%);
        color: white;
        cursor: pointer;
        transition: all 0.15s ease;
        font-size: 1rem;
        min-width: 200px;
        max-width: 100%;
        margin-left: auto;
        box-shadow: 0 10px 20px rgba(3, 102, 214, 0.22);
    }
    .composer .send:hover { transform: translateY(-1px); box-shadow: 0 14px 26px rgba(3, 102, 214, 0.26); }
</style>
