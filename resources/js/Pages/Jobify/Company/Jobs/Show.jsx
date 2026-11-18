import JobifyLayout from '../../../../Layouts/JobifyLayout';
import { Link, router } from '@inertiajs/react';
import { useState, useMemo, useRef, useEffect } from 'react';

// ── helpers ──────────────────────────────────────────────────────────────────
const STAGE_COLORS = ['#F97316','#6366F1','#9E0059','#0D9488','#EAB308','#22C55E','#EF4444'];
function stageColor(idx) { return STAGE_COLORS[idx % STAGE_COLORS.length]; }

function scoreClass(score) {
    if (!score) return 'bg-gray-100 text-gray-500';
    if (score >= 80) return 'bg-green-100 text-green-700';
    if (score >= 60) return 'bg-yellow-100 text-yellow-700';
    if (score >= 40) return 'bg-orange-100 text-orange-700';
    return 'bg-red-100 text-red-600';
}

function initials(app) {
    const u = app.candidate?.user ?? {};
    const f = u.first_name?.[0] ?? u.name?.[0] ?? '';
    const l = u.last_name?.[0] ?? '';
    return (f + l).toUpperCase() || '#';
}

function candidateName(app) {
    const u = app.candidate?.user ?? {};
    return [u.first_name ?? u.name, u.last_name].filter(Boolean).join(' ') || `Candidate #${app.id}`;
}

function shortDate(d) {
    if (!d) return '';
    return new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
}

function createdDate(d) {
    if (!d) return '';
    return new Date(d).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
}

function csrf() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
}

// ── sort header (defined outside component to prevent remounting on re-render)
function SortTh({ label, k, sortKey, sortDir, doSort }) {
    return (
        <th className="px-4 py-3 whitespace-nowrap">
            <button onClick={() => doSort(k)} className="flex items-center gap-1 hover:text-gray-700 transition text-xs font-semibold text-gray-400">
                {label}
                <span className="inline-flex flex-col gap-px ml-0.5">
                    <svg viewBox="0 0 6 4" className="w-2 h-1.5 fill-current" style={{ color: sortKey === k && sortDir === 'asc' ? '#38009A' : '#D1D5DB' }}><path d="M3 0L6 4H0Z"/></svg>
                    <svg viewBox="0 0 6 4" className="w-2 h-1.5 fill-current" style={{ color: sortKey === k && sortDir === 'desc' ? '#38009A' : '#D1D5DB' }}><path d="M3 4L0 0H6Z"/></svg>
                </span>
            </button>
        </th>
    );
}

// ── main component ────────────────────────────────────────────────────────────
export default function JobShow({ org, job }) {
    const storedView = typeof localStorage !== 'undefined' ? (localStorage.getItem('pipeline_view') ?? 'board') : 'board';
    const [viewMode, setViewMode] = useState(storedView);

    function switchView(v) { setViewMode(v); localStorage.setItem('pipeline_view', v); }

    // pipeline stages (mutable for optimistic DnD)
    const [pipeline, setPipeline] = useState(() =>
        (job.pipeline_stages ?? []).map(s => ({ ...s, applications: [...(s.applications ?? [])] }))
    );

    // keep pipeline in sync when Inertia reloads the page
    useEffect(() => {
        setPipeline((job.pipeline_stages ?? []).map(s => ({ ...s, applications: [...(s.applications ?? [])] })));
    }, [job]);

    // ── stage management ──────────────────────────────────────────────────
    const [managingStages, setManagingStages] = useState(false);
    const [newStageName, setNewStageName] = useState('');
    const [editingStage, setEditingStage] = useState(null); // { id, name }
    const [savingStage, setSavingStage]  = useState(false);

    function addStage(e) {
        e.preventDefault();
        if (!newStageName.trim() || savingStage) return;
        setSavingStage(true);
        router.post(`/employer/jobs/${job.id}/stages`, { name: newStageName.trim() }, {
            preserveScroll: true,
            onSuccess: () => { setNewStageName(''); setSavingStage(false); },
            onError: () => setSavingStage(false),
        });
    }

    function saveRenameStage() {
        if (!editingStage?.name.trim()) return;
        router.patch(`/employer/stages/${editingStage.id}`, { name: editingStage.name.trim() }, {
            preserveScroll: true,
            onSuccess: () => setEditingStage(null),
        });
    }

    function deleteStage(stageId) {
        const stage = pipeline.find(s => s.id === stageId);
        if ((stage?.applications?.length ?? 0) > 0) { alert('Stage must be empty before deleting.'); return; }
        if (!confirm('Delete this stage?')) return;
        router.delete(`/employer/stages/${stageId}`, { preserveScroll: true });
    }

    // ── board DnD ─────────────────────────────────────────────────────────
    const draggingAppId = useRef(null);

    function onDragStart(appId) { draggingAppId.current = appId; }

    function onDrop(targetStageId) {
        const appId = draggingAppId.current;
        if (!appId) return;

        // optimistic move
        let movedApp = null;
        let sourceStageIdx = -1;
        const next = pipeline.map((s, i) => {
            const idx = s.applications.findIndex(a => a.id === appId);
            if (idx !== -1) { movedApp = s.applications[idx]; sourceStageIdx = i; return { ...s, applications: s.applications.filter(a => a.id !== appId) }; }
            return s;
        });
        if (!movedApp) return;
        const updated = next.map(s => s.id === targetStageId ? { ...s, applications: [...s.applications, { ...movedApp, pipeline_stage_id: targetStageId }] } : s);
        setPipeline(updated);
        draggingAppId.current = null;

        router.patch(`/employer/applications/${movedApp.id}/move-stage`, { stage_id: targetStageId }, {
            preserveScroll: true,
            onError: () => setPipeline(pipeline), // revert
        });
    }

    // ── list view state ───────────────────────────────────────────────────
    const allApplications = useMemo(() =>
        pipeline.flatMap((s, i) => (s.applications ?? []).map(a => ({ ...a, _stageName: s.name, _stageId: s.id, _stageIdx: i }))),
        [pipeline]
    );

    const [search, setSearch]           = useState('');
    const [activeStage, setActiveStage] = useState(null);
    const [sortKey, setSortKey]         = useState(null);
    const [sortDir, setSortDir]         = useState('asc');
    const [pageSize, setPageSize]       = useState(20);
    const [stagePickerApp, setStagePickerApp] = useState(null);
    const pickerRef = useRef(null);

    useEffect(() => {
        const h = e => { if (pickerRef.current && !pickerRef.current.contains(e.target)) setStagePickerApp(null); };
        document.addEventListener('click', h);
        return () => document.removeEventListener('click', h);
    }, []);

    function doSort(key) {
        if (sortKey === key) setSortDir(d => d === 'asc' ? 'desc' : 'asc');
        else { setSortKey(key); setSortDir('asc'); }
    }

    function sortVal(app, key) {
        if (key === 'name')  return candidateName(app).toLowerCase();
        if (key === 'date')  return new Date(app.created_at).getTime();
        if (key === 'score') return app.match_score ?? -1;
        if (key === 'stage') return app._stageIdx;
        return 0;
    }

    const filtered = useMemo(() => {
        let apps = allApplications;
        if (activeStage !== null) apps = apps.filter(a => a._stageId === activeStage);
        if (search.trim()) {
            const q = search.toLowerCase();
            apps = apps.filter(a => candidateName(a).toLowerCase().includes(q) || (a.candidate?.user?.email ?? '').toLowerCase().includes(q));
        }
        if (sortKey) {
            const dir = sortDir === 'asc' ? 1 : -1;
            apps = [...apps].sort((a, b) => {
                const av = sortVal(a, sortKey), bv = sortVal(b, sortKey);
                return av < bv ? -dir : av > bv ? dir : 0;
            });
        }
        return apps;
    }, [allApplications, activeStage, search, sortKey, sortDir]);

    function moveStagePicker(app, stage) {
        setStagePickerApp(null);
        const next = pipeline.map(s => ({
            ...s,
            applications: s.id === app._stageId
                ? s.applications.filter(a => a.id !== app.id)
                : s.id === stage.id
                    ? [...s.applications, { ...app, pipeline_stage_id: stage.id }]
                    : s.applications,
        }));
        setPipeline(next);
        router.patch(`/employer/applications/${app.id}/move-stage`, { stage_id: stage.id }, { preserveScroll: true, onError: () => setPipeline(pipeline) });
    }

    // ── AI rank ───────────────────────────────────────────────────────────
    const [aiLoading, setAiLoading] = useState(false);
    const [aiMsg, setAiMsg]         = useState('');

    async function runAiRank() {
        setAiLoading(true); setAiMsg('');
        try {
            const res  = await fetch(`/employer/jobs/${job.id}/ai-rank`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
            });
            const data = await res.json();
            if (!res.ok) { setAiMsg(data.error ?? 'AI ranking failed.'); }
            else { setAiMsg('✓ Scores updated'); router.reload({ only: ['job'] }); }
        } catch { setAiMsg('Network error.'); }
        setAiLoading(false);
        setTimeout(() => setAiMsg(''), 3000);
    }

    // ── actions ───────────────────────────────────────────────────────────
    function publish() { router.patch(`/employer/jobs/${job.id}/publish`, {}, { preserveScroll: true }); }
    function destroy() {
        if (!confirm('Delete this job and all its pipeline data? This cannot be undone.')) return;
        router.delete(`/employer/jobs/${job.id}`, { onSuccess: () => router.visit('/employer/jobs') });
    }


    return (
        <JobifyLayout title={`${job.title} — Pipeline`} noPadding>
            <div className="flex flex-col bg-white" style={{ minHeight: 'calc(100vh - 60px)' }}>

                {/* ── PAGE HEADER ── */}
                <div className="shrink-0 border-b border-gray-100 bg-white">

                    {/* Breadcrumb */}
                    <div className="flex items-center gap-1.5 px-6 pt-4 text-xs text-gray-400">
                        <span>Employer workspace</span>
                        <svg className="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7"/></svg>
                        <Link href="/employer/jobs" className="hover:text-gray-600 transition">Job listings</Link>
                        <svg className="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7"/></svg>
                        <span className="text-gray-600 font-medium truncate max-w-[200px]">{job.title}</span>
                    </div>

                    {/* Title row */}
                    <div className="flex items-center gap-3 px-6 py-3 flex-wrap">
                        <Link href="/employer/jobs"
                            className="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
                            <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </Link>
                        <h1 className="text-xl font-bold text-gray-900">{job.title}</h1>
                        <span className="rounded-md bg-blue-50 px-2 py-0.5 text-xs font-semibold text-blue-600 border border-blue-100">
                            JR {String(job.id).padStart(6, '0')}
                        </span>

                        <div className="flex-1" />

                        {/* Board / List toggle */}
                        <div className="flex items-center rounded-full p-1 gap-0.5" style={{ background: '#EBE6F5' }}>
                            {[['board', 'M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z', 'Board'],
                             ['list',  'M4 6h16M4 10h16M4 14h16M4 18h16', 'List']].map(([v, path, label]) => (
                                <button key={v} onClick={() => switchView(v)}
                                    className="flex items-center gap-1.5 rounded-full px-3 py-1.5 text-sm font-medium transition"
                                    style={viewMode === v ? { background: 'white', color: '#38009A', boxShadow: '0 1px 3px rgba(0,0,0,0.08)' } : { color: '#9CA3AF' }}>
                                    <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d={path}/></svg>
                                    {label}
                                </button>
                            ))}
                        </div>

                        {/* AI Rank */}
                        <div className="relative">
                            <button onClick={runAiRank} disabled={aiLoading}
                                className="flex items-center gap-1.5 rounded-lg border border-purple-200 px-3 py-1.5 text-xs font-medium transition disabled:opacity-60 hover:opacity-80"
                                style={{ background: '#EBE6F5', color: '#38009A' }}>
                                ✨ {aiLoading ? 'Ranking…' : 'AI Rank'}
                            </button>
                            {aiMsg && <p className="absolute right-0 top-9 z-40 w-48 rounded-lg border px-3 py-2 text-xs" style={{ background: aiMsg.startsWith('✓') ? '#F0FDF4' : '#FEF2F2', color: aiMsg.startsWith('✓') ? '#16A34A' : '#DC2626', borderColor: aiMsg.startsWith('✓') ? '#BBF7D0' : '#FECACA' }}>{aiMsg}</p>}
                        </div>

                        {/* Automations */}
                        <Link href={`/employer/jobs/${job.id}/automations`}
                            className="flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 text-gray-400 hover:bg-gray-50 transition" title="Stage Automations">
                            <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </Link>

                        {/* Stage management toggle */}
                        <button onClick={() => setManagingStages(m => !m)}
                            className="flex h-8 w-8 items-center justify-center rounded-full border transition"
                            style={managingStages ? { borderColor: '#FCD34D', background: '#FFFBEB', color: '#D97706' } : { borderColor: '#E5E7EB', color: '#9CA3AF' }}
                            title="Manage stages">
                            <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </button>

                        {/* Publish / Pause */}
                        <button onClick={publish}
                            className="flex h-8 w-8 items-center justify-center rounded-full border transition"
                            style={job.status === 'active' ? { borderColor: '#FCD34D', background: '#FFFBEB', color: '#D97706' } : { borderColor: '#BBF7D0', background: '#F0FDF4', color: '#16A34A' }}
                            title={job.status === 'active' ? 'Pause job' : 'Publish job'}>
                            {job.status === 'active'
                                ? <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M10 9v6m4-6v6"/></svg>
                                : <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7"/></svg>}
                        </button>

                        {/* Edit */}
                        <Link href={`/employer/jobs/${job.id}/edit`}
                            className="flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 text-gray-400 hover:bg-gray-50 transition" title="Edit job">
                            <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </Link>

                        {/* Delete */}
                        <button onClick={destroy}
                            className="flex h-8 w-8 items-center justify-center rounded-full border border-red-200 text-red-400 hover:bg-red-50 transition" title="Delete job">
                            <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>

                    {/* Info bar */}
                    <div className="flex items-center flex-wrap gap-x-2 gap-y-1 px-6 pb-3 text-xs text-gray-500">
                        <span>Created {createdDate(job.created_at)}</span>
                        <span className="text-gray-300">·</span>
                        <span>{allApplications.length} candidates</span>
                        <span className="text-gray-300">·</span>
                        <span className="capitalize">{job.employment_type?.replace(/_/g, ' ')}</span>
                        {job.location && <><span className="text-gray-300">·</span><span>{job.location}</span></>}
                        {(job.salary_min || job.salary_max) && <><span className="text-gray-300">·</span><span>{job.currency} {job.salary_min?.toLocaleString()} – {job.salary_max?.toLocaleString()}</span></>}
                    </div>

                    {/* Stage management panel */}
                    {managingStages && (
                        <div className="mx-6 mb-3 rounded-xl border border-amber-200 bg-amber-50 px-5 py-4">
                            <h3 className="text-sm font-semibold text-amber-800 mb-3">Manage Pipeline Stages</h3>
                            <div className="space-y-2 mb-4">
                                {pipeline.map(stage => (
                                    <div key={stage.id} className="flex items-center gap-3 rounded-lg bg-white px-3 py-2 ring-1 ring-amber-100">
                                        {editingStage?.id === stage.id ? (
                                            <>
                                                <input value={editingStage.name} onChange={e => setEditingStage(s => ({ ...s, name: e.target.value }))}
                                                    onKeyDown={e => { if (e.key === 'Enter') saveRenameStage(); if (e.key === 'Escape') setEditingStage(null); }}
                                                    className="flex-1 rounded-md border border-gray-300 px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400" autoFocus />
                                                <button className="text-xs font-semibold text-amber-700 hover:text-amber-900" onClick={saveRenameStage}>Save</button>
                                                <button className="text-xs text-gray-500" onClick={() => setEditingStage(null)}>Cancel</button>
                                            </>
                                        ) : (
                                            <>
                                                <span className="flex-1 text-sm text-gray-800">{stage.name}</span>
                                                <span className="text-xs text-gray-400">{stage.applications?.length ?? 0} apps</span>
                                                <button className="text-xs text-indigo-600 hover:text-indigo-800" onClick={() => setEditingStage({ id: stage.id, name: stage.name })}>Rename</button>
                                                <button className="text-xs text-red-500 hover:text-red-700 disabled:opacity-40" disabled={(stage.applications?.length ?? 0) > 0} onClick={() => deleteStage(stage.id)}>Delete</button>
                                            </>
                                        )}
                                    </div>
                                ))}
                            </div>
                            <form className="flex gap-2" onSubmit={addStage}>
                                <input value={newStageName} onChange={e => setNewStageName(e.target.value)} required placeholder="New stage name…"
                                    className="flex-1 rounded-lg border border-amber-300 px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400" />
                                <button type="submit" disabled={savingStage} className="rounded-lg bg-amber-600 px-4 py-1.5 text-sm font-semibold text-white hover:bg-amber-700 disabled:opacity-50">Add Stage</button>
                            </form>
                        </div>
                    )}

                    {/* Pipeline bar */}
                    <div className="flex items-center justify-between border-t border-gray-100 bg-gray-50 px-6 py-2">
                        <span className="text-xs text-gray-500 font-medium">
                            Hiring pipeline
                            <span className="text-gray-400 font-normal"> · {allApplications.length} candidates across {pipeline.length} stages</span>
                        </span>
                        <span className="flex items-center gap-1.5 rounded-full bg-green-50 border border-green-200 px-2.5 py-1 text-[11px] font-semibold text-green-700">
                            <span className="h-1.5 w-1.5 rounded-full bg-green-500 inline-block" />
                            {job.status === 'active' ? 'Active' : job.status?.charAt(0).toUpperCase() + job.status?.slice(1)}
                        </span>
                    </div>
                </div>

                {/* ── BOARD VIEW ── */}
                {viewMode === 'board' && (
                    <div className="flex-1 overflow-x-auto bg-gray-100">
                        <div className="flex gap-3 p-3" style={{ height: 'calc(100vh - 260px)', minHeight: '400px' }}>
                            {pipeline.length === 0 && (
                                <div className="flex-1 flex items-center justify-center text-gray-400 text-sm">
                                    No stages yet. Click the ⚙ icon to add stages.
                                </div>
                            )}
                            {pipeline.map((stage, idx) => (
                                <div key={stage.id}
                                    className="flex flex-col min-w-[200px] flex-1 shrink-0 bg-white rounded-xl h-full"
                                    onDragOver={e => e.preventDefault()}
                                    onDrop={() => onDrop(stage.id)}>

                                    {/* Column header */}
                                    <div className="flex items-center justify-between px-3 py-3 border-b border-gray-100 shrink-0">
                                        <div className="flex items-center gap-1.5">
                                            <span className="h-2 w-2 rounded-full shrink-0" style={{ background: stageColor(idx) }} />
                                            <span className="text-xs font-semibold text-gray-700 truncate">{stage.name}</span>
                                        </div>
                                        <span className="text-xs font-semibold text-gray-400 ml-1">{stage.applications?.length ?? 0}</span>
                                    </div>

                                    {/* Cards */}
                                    <div className="flex flex-col gap-2 flex-1 overflow-y-auto p-3 min-h-0">
                                        {(!stage.applications || stage.applications.length === 0) && (
                                            <div className="flex flex-col items-center justify-center py-8 text-center">
                                                <svg className="h-10 w-10 text-gray-200 mb-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                                                <p className="text-xs text-gray-300">No candidates</p>
                                            </div>
                                        )}
                                        {(stage.applications ?? []).map(app => (
                                            <div key={app.id}
                                                draggable
                                                onDragStart={() => onDragStart(app.id)}
                                                className="rounded-xl bg-white border shadow-sm px-3 py-2.5 cursor-grab active:cursor-grabbing transition-all border-l-[3px]"
                                                style={{ borderLeftColor: stageColor(idx) }}>

                                                {/* Name + score */}
                                                <div className="flex items-center justify-between gap-2 mb-1.5">
                                                    <Link href={`/employer/applications/${app.id}`}
                                                        className="text-[13px] font-bold text-gray-900 hover:text-[#38009A] transition truncate leading-tight">
                                                        {candidateName(app)}
                                                    </Link>
                                                    {app.match_score && (
                                                        <span className="shrink-0 rounded-full h-7 w-7 flex items-center justify-center text-[11px] font-bold" style={{ background: '#EBE6F5', color: '#38009A' }}>
                                                            {app.match_score}
                                                        </span>
                                                    )}
                                                </div>

                                                {/* Avatar + date */}
                                                <div className="flex items-center gap-1.5 mb-2">
                                                    <div className="h-5 w-5 shrink-0 rounded flex items-center justify-center text-[9px] font-bold text-white"
                                                        style={{ background: stageColor(idx) }}>
                                                        {initials(app)}
                                                    </div>
                                                    <svg className="h-3 w-3 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                                    <span className="text-[11px] text-gray-500">{shortDate(app.created_at)}</span>
                                                </div>

                                                {/* View button */}
                                                <Link href={`/employer/applications/${app.id}`}
                                                    className="flex items-center justify-center gap-1 w-full rounded-lg py-1.5 text-[11px] font-semibold transition"
                                                    style={{ background: '#EBE6F5', color: '#38009A' }}>
                                                    View Profile
                                                    <svg className="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2.5}><path strokeLinecap="round" strokeLinejoin="round" d="M9 5l7 7-7 7"/></svg>
                                                </Link>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                )}

                {/* ── LIST VIEW ── */}
                {viewMode === 'list' && (
                    <div className="flex-1 overflow-auto bg-white" onClick={() => setStagePickerApp(null)}>

                        {/* Stage tabs + search */}
                        <div className="flex items-center gap-2 border-b border-gray-100 px-4 py-2 overflow-x-auto">
                            <button onClick={() => { setActiveStage(null); setSearch(''); }}
                                className="flex shrink-0 items-center gap-1 px-3 py-1.5 text-sm font-medium rounded-full transition"
                                style={activeStage === null ? { background: '#EBE6F5', color: '#38009A' } : { color: '#6B7280' }}>
                                All <span className="text-[11px] font-bold">{allApplications.length}</span>
                            </button>
                            {pipeline.map((stage, si) => (
                                <button key={stage.id} onClick={() => { setActiveStage(stage.id); setSearch(''); }}
                                    className="flex shrink-0 items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-full transition"
                                    style={activeStage === stage.id ? { background: '#EBE6F5', color: '#38009A' } : { color: '#6B7280' }}>
                                    <span className="h-1.5 w-1.5 rounded-full shrink-0" style={{ background: stageColor(si) }} />
                                    {stage.name}
                                    <span className="text-[11px] font-bold">{stage.applications?.length ?? 0}</span>
                                </button>
                            ))}
                            <div className="flex-1" />
                            <div className="relative shrink-0 w-48">
                                <svg className="absolute left-3 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/></svg>
                                <input value={search} onChange={e => setSearch(e.target.value)} placeholder="Search candidates…"
                                    className="w-full rounded-lg border border-gray-200 bg-gray-50 py-1.5 pl-8 pr-3 text-xs focus:border-[#38009A] focus:bg-white focus:outline-none" />
                            </div>
                        </div>

                        {/* Table */}
                        <div className="overflow-x-auto">
                            <table className="w-full text-sm text-left">
                                <thead>
                                    <tr className="border-b border-gray-100 select-none">
                                        <SortTh label="Candidate" k="name"  sortKey={sortKey} sortDir={sortDir} doSort={doSort} />
                                        <SortTh label="Applied"   k="date"  sortKey={sortKey} sortDir={sortDir} doSort={doSort} />
                                        <SortTh label="CV Score"  k="score" sortKey={sortKey} sortDir={sortDir} doSort={doSort} />
                                        <SortTh label="Stage"     k="stage" sortKey={sortKey} sortDir={sortDir} doSort={doSort} />
                                        <th className="px-3 py-3 w-8" />
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-50">
                                    {filtered.length === 0 && (
                                        <tr><td colSpan={5} className="px-4 py-12 text-center text-sm text-gray-400">No candidates found.</td></tr>
                                    )}
                                    {filtered.slice(0, pageSize).map(app => (
                                        <tr key={app.id} className="hover:bg-gray-50/70 transition-colors group">
                                            {/* Candidate */}
                                            <td className="px-4 py-3">
                                                <Link href={`/employer/applications/${app.id}`} className="flex items-center gap-3">
                                                    <div className="h-9 w-9 shrink-0 rounded-lg flex items-center justify-center text-xs font-bold text-white"
                                                        style={{ background: stageColor(app._stageIdx) }}>
                                                        {initials(app)}
                                                    </div>
                                                    <div className="min-w-0">
                                                        <p className="font-semibold text-gray-900 text-sm group-hover:text-[#38009A] transition truncate">{candidateName(app)}</p>
                                                        <p className="text-[11px] text-gray-400 truncate">{app.candidate?.user?.email}</p>
                                                    </div>
                                                </Link>
                                            </td>
                                            {/* Applied */}
                                            <td className="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">{shortDate(app.created_at)}</td>
                                            {/* Score */}
                                            <td className="px-4 py-3">
                                                {app.match_score
                                                    ? <span className="rounded px-2 py-0.5 text-xs font-bold" style={{ background: '#EBE6F5', color: '#38009A' }}>{app.match_score}</span>
                                                    : <span className="text-gray-300 text-sm">—</span>}
                                            </td>
                                            {/* Stage picker */}
                                            <td className="px-4 py-3 relative" ref={app.id === stagePickerApp ? pickerRef : null}>
                                                <button onClick={e => { e.stopPropagation(); setStagePickerApp(a => a === app.id ? null : app.id); }}
                                                    className="rounded-full px-2.5 py-0.5 text-xs font-semibold whitespace-nowrap transition hover:opacity-80"
                                                    style={{ background: stageColor(app._stageIdx) + '22', color: stageColor(app._stageIdx) }}>
                                                    {app._stageName}
                                                </button>
                                                {stagePickerApp === app.id && (
                                                    <div className="absolute left-0 top-9 z-30 w-48 rounded-xl border border-gray-100 bg-white shadow-xl py-1">
                                                        {pipeline.map((stage, si) => (
                                                            <button key={stage.id} onClick={e => { e.stopPropagation(); moveStagePicker(app, stage); }}
                                                                className="flex w-full items-center justify-between px-3 py-2 text-sm hover:bg-gray-50 transition"
                                                                style={app._stageId === stage.id ? { fontWeight: 600, color: '#38009A' } : { color: '#374151' }}>
                                                                <span className="flex items-center gap-2">
                                                                    <span className="h-1.5 w-1.5 rounded-full" style={{ background: stageColor(si) }} />
                                                                    {stage.name}
                                                                </span>
                                                                {app._stageId === stage.id && <svg className="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2.5} style={{ color: '#38009A' }}><path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7"/></svg>}
                                                            </button>
                                                        ))}
                                                    </div>
                                                )}
                                            </td>
                                            {/* View */}
                                            <td className="px-3 py-3">
                                                <Link href={`/employer/applications/${app.id}`}
                                                    className="flex h-7 items-center gap-1 rounded-lg px-2 text-[11px] font-semibold transition whitespace-nowrap"
                                                    style={{ background: '#EBE6F5', color: '#38009A' }}>
                                                    View
                                                </Link>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        {/* Footer */}
                        <div className="flex items-center justify-between border-t border-gray-100 px-4 py-3">
                            <p className="text-xs text-gray-500">Showing {Math.min(pageSize, filtered.length)} of {filtered.length} candidates</p>
                            {pageSize < filtered.length && (
                                <button onClick={() => setPageSize(p => p + 20)}
                                    className="rounded-lg border border-gray-200 bg-white px-4 py-1.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                                    Load more
                                </button>
                            )}
                        </div>
                    </div>
                )}
            </div>
        </JobifyLayout>
    );
}
