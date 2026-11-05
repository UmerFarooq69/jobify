import JobifyLayout from '../../../../Layouts/JobifyLayout';
import { Link, router } from '@inertiajs/react';
import { useState } from 'react';
import { Zap, Mail, Users, ClipboardList, Calendar, ChevronDown } from 'lucide-react';

const ACTION_META = {
    send_email:         { label: 'Send Email to Candidate', color: '#1D4ED8', bg: '#EFF6FF', icon: Mail },
    notify_team:        { label: 'Notify Team',             color: '#7C3AED', bg: '#F5F3FF', icon: Users },
    request_assessment: { label: 'Request Assessment',      color: '#92400E', bg: '#FFFBEB', icon: ClipboardList },
    schedule_interview: { label: 'Schedule Interview',      color: '#065F46', bg: '#ECFDF5', icon: Calendar },
};

const STAGE_COLORS = ['#F97316','#6366F1','#9E0059','#0D9488','#EAB308','#22C55E','#EF4444'];
function stageColor(idx) { return STAGE_COLORS[idx % STAGE_COLORS.length]; }

function configSummary(auto) {
    const c = auto.action_config;
    if (!c) return null;
    if (typeof c === 'string') return c;
    if (c.subject) return `Subject: ${c.subject}`;
    if (c.notes)   return c.notes;
    return null;
}

function Toggle({ active, onToggle }) {
    return (
        <button onClick={onToggle}
            className="relative inline-flex h-5 w-9 shrink-0 cursor-pointer items-center rounded-full overflow-hidden transition-colors focus:outline-none"
            style={{ background: active ? '#38009A' : '#D1D5DB' }}>
            <span className="inline-block h-3 w-3 transform rounded-full bg-white shadow transition-transform"
                style={{ transform: active ? 'translateX(20px)' : 'translateX(4px)' }} />
        </button>
    );
}

function AutomationForm({ stage, onCancel, onSaved }) {
    const [actionType, setActionType] = useState('send_email');
    const [subject, setSubject]       = useState('');
    const [body, setBody]             = useState('');
    const [notes, setNotes]           = useState('');
    const [saving, setSaving]         = useState(false);

    function submit(e) {
        e.preventDefault();
        if (saving) return;
        setSaving(true);
        const config = actionType === 'notify_team'
            ? (notes ? { notes } : null)
            : (subject || body ? { ...(subject ? { subject } : {}), ...(body ? { body } : {}) } : null);

        router.post(`/employer/jobs/${stage._jobId}/automations`, {
            stage_id: stage.id,
            action_type: actionType,
            action_config: config,
        }, {
            preserveScroll: true,
            onSuccess: () => { setSaving(false); onSaved(); },
            onError: () => setSaving(false),
        });
    }

    const isEmail = actionType !== 'notify_team';
    const placeholders = {
        schedule_interview: { subject: 'Interview Invitation — {{job_title}}', body: 'Write a custom message or leave blank for the default invite email.' },
        request_assessment: { subject: 'Assessment Request — {{job_title}}',   body: 'Write a custom message or leave blank for the default assessment email.' },
        send_email:         { subject: 'Application Update — {{job_title}}',   body: 'Write a custom message to the candidate, or leave blank for the default.' },
    };
    const ph = placeholders[actionType] ?? placeholders.send_email;

    return (
        <form onSubmit={submit} className="border-t border-gray-100 rounded-b-xl px-5 py-5 space-y-4" style={{ background: '#F5F0FF' }}>
            {/* Action type */}
            <div>
                <label className="block text-xs font-semibold text-gray-600 mb-1.5">Action Type</label>
                <select value={actionType} onChange={e => setActionType(e.target.value)}
                    className="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-[#38009A] focus:outline-none">
                    <option value="send_email">Send Email to Candidate</option>
                    <option value="notify_team">Notify Team</option>
                    <option value="request_assessment">Request Assessment</option>
                    <option value="schedule_interview">Schedule Interview</option>
                </select>
            </div>

            {isEmail ? (
                <>
                    <div>
                        <label className="block text-xs font-semibold text-gray-600 mb-1">Email Subject <span className="font-normal text-gray-400">(leave blank for default)</span></label>
                        <input value={subject} onChange={e => setSubject(e.target.value)} placeholder={ph.subject}
                            className="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-[#38009A] focus:outline-none" />
                    </div>
                    <div>
                        <label className="block text-xs font-semibold text-gray-600 mb-1">Email Body <span className="font-normal text-gray-400">(leave blank for default)</span></label>
                        <textarea value={body} onChange={e => setBody(e.target.value)} rows={3} placeholder={ph.body}
                            className="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-[#38009A] focus:outline-none resize-none" />
                    </div>
                </>
            ) : (
                <div>
                    <label className="block text-xs font-semibold text-gray-600 mb-1">Internal Note <span className="font-normal text-gray-400">(optional, included in team email)</span></label>
                    <textarea value={notes} onChange={e => setNotes(e.target.value)} rows={2} placeholder="e.g. Candidate needs technical review this week."
                        className="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-[#38009A] focus:outline-none resize-none" />
                </div>
            )}

            <div className="flex gap-2">
                <button type="submit" disabled={saving}
                    className="rounded-lg px-4 py-2 text-sm font-semibold text-white transition disabled:opacity-60 hover:opacity-90"
                    style={{ background: '#38009A' }}>
                    {saving ? 'Saving…' : 'Save Automation'}
                </button>
                <button type="button" onClick={onCancel}
                    className="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </button>
            </div>
        </form>
    );
}

export default function Automations({ job, stages, automations }) {
    const [openForm, setOpenForm] = useState(null); // stage id

    function automationsForStage(stageId) {
        return (automations ?? []).filter(a => Number(a.stage_id) === Number(stageId));
    }

    function toggle(autoId) {
        router.patch(`/employer/automations/${autoId}/toggle`, {}, { preserveScroll: true });
    }

    function remove(autoId) {
        if (!confirm('Remove this automation permanently?')) return;
        router.delete(`/employer/automations/${autoId}`, { preserveScroll: true });
    }

    return (
        <JobifyLayout title={`Automations — ${job.title}`}>
            <div className="max-w-4xl space-y-5">

                {/* Header */}
                <div className="rounded-2xl p-6 text-white relative overflow-hidden" style={{ background: 'linear-gradient(135deg, #1F0055 0%, #38009A 100%)' }}>
                    <div className="relative z-10">
                        <div className="flex items-center gap-3 mb-1">
                            <Link href={`/employer/jobs/${job.id}`}
                                className="flex items-center gap-1.5 text-white/70 hover:text-white text-sm transition">
                                <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}><path strokeLinecap="round" strokeLinejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                Pipeline
                            </Link>
                            <span className="text-white/30">/</span>
                            <div className="flex items-center gap-2">
                                <Zap size={18} className="text-yellow-300" />
                                <h1 className="text-xl font-bold">Stage Automations</h1>
                            </div>
                        </div>
                        <p className="text-white/60 text-sm">{job.title} — trigger emails and notifications when candidates move between stages.</p>
                    </div>
                    <div className="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-white/5" />
                    <div className="absolute -right-4 -bottom-10 h-48 w-48 rounded-full bg-white/5" />
                </div>

                <p className="text-sm text-gray-500 px-1">
                    Automations fire automatically when a candidate enters a pipeline stage.
                </p>

                {/* Per-stage cards */}
                {(stages ?? []).length === 0 && (
                    <div className="rounded-2xl bg-white px-6 py-12 text-center shadow-sm ring-1 ring-gray-100">
                        <p className="text-sm text-gray-400">No pipeline stages found. Add stages to the job first.</p>
                        <Link href={`/employer/jobs/${job.id}`} className="mt-3 inline-block text-sm font-semibold hover:underline" style={{ color: '#38009A' }}>← Go to Pipeline</Link>
                    </div>
                )}

                {(stages ?? []).map((stage, si) => {
                    const stageAutomations = automationsForStage(stage.id);
                    const color = stageColor(si);
                    const enrichedStage = { ...stage, _jobId: job.id };

                    return (
                        <div key={stage.id} className="rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 overflow-hidden">

                            {/* Stage header */}
                            <div className="flex items-center justify-between border-b border-gray-100 bg-gray-50 px-5 py-3 rounded-t-2xl">
                                <div className="flex items-center gap-2">
                                    <span className="h-2 w-2 rounded-full" style={{ background: color }} />
                                    <h3 className="text-sm font-semibold text-gray-800">{stage.name}</h3>
                                    <span className="rounded-full px-2 py-0.5 text-[11px] font-semibold" style={{ background: color + '22', color }}>
                                        {stageAutomations.length} automation{stageAutomations.length !== 1 ? 's' : ''}
                                    </span>
                                </div>
                                <button onClick={() => setOpenForm(f => f === stage.id ? null : stage.id)}
                                    className="flex items-center gap-1.5 rounded-lg border px-3 py-1 text-xs font-medium transition"
                                    style={{ borderColor: '#C4B5FD', color: '#38009A', background: openForm === stage.id ? '#EBE6F5' : 'white' }}>
                                    {openForm === stage.id ? '✕ Cancel' : '+ Add Automation'}
                                </button>
                            </div>

                            {/* Automations list */}
                            <div className="px-5 py-3 space-y-2">
                                {stageAutomations.length === 0 && openForm !== stage.id && (
                                    <p className="text-xs text-gray-300 py-1">No automations configured for this stage.</p>
                                )}
                                {stageAutomations.map(auto => {
                                    const meta = ACTION_META[auto.action_type] ?? { label: auto.action_type, color: '#6B7280', bg: '#F3F4F6', icon: Zap };
                                    const Icon = meta.icon;
                                    return (
                                        <div key={auto.id} className="flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50 px-4 py-2.5">
                                            <div className="flex items-center gap-3 min-w-0">
                                                <div className="h-7 w-7 rounded-lg flex items-center justify-center shrink-0" style={{ background: meta.bg }}>
                                                    <Icon size={14} style={{ color: meta.color }} />
                                                </div>
                                                <span className="rounded-full px-2.5 py-0.5 text-xs font-medium shrink-0" style={{ background: meta.bg, color: meta.color }}>
                                                    {meta.label}
                                                </span>
                                                {configSummary(auto) && (
                                                    <span className="truncate text-xs text-gray-400">{configSummary(auto)}</span>
                                                )}
                                                {!configSummary(auto) && (
                                                    <span className="text-xs text-gray-300 italic">Default template</span>
                                                )}
                                            </div>
                                            <div className="ml-4 flex shrink-0 items-center gap-3">
                                                <span className="text-xs text-gray-400 w-5 text-right">{auto.is_active ? 'On' : 'Off'}</span>
                                                <Toggle active={auto.is_active} onToggle={() => toggle(auto.id)} />
                                                <button onClick={() => remove(auto.id)} className="text-xs font-medium text-red-400 hover:text-red-600 transition">Remove</button>
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>

                            {/* Add form */}
                            {openForm === stage.id && (
                                <AutomationForm
                                    stage={enrichedStage}
                                    onCancel={() => setOpenForm(null)}
                                    onSaved={() => setOpenForm(null)}
                                />
                            )}
                        </div>
                    );
                })}
            </div>
        </JobifyLayout>
    );
}
