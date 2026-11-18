import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';

const PALETTE = ['#9E0059','#38009A','#0D9488','#F97316','#6366F1','#059669'];
function avatarColor(str) {
    let h = 0;
    for (let i = 0; i < str.length; i++) h = (h * 31 + str.charCodeAt(i)) & 0xffff;
    return PALETTE[h % PALETTE.length];
}
function candidateName(app) {
    const u = app.candidate?.user ?? {};
    return [u.first_name ?? u.name, u.last_name].filter(Boolean).join(' ') || 'Unknown';
}
function initials(app) {
    const name = candidateName(app);
    return name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
}
function shortDate(val) {
    if (!val) return '—';
    return new Date(val).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

function Accordion({ title, defaultOpen = false, children }) {
    const [open, setOpen] = useState(defaultOpen);
    return (
        <div className="border-b border-gray-200 last:border-0">
            <button onClick={() => setOpen(o => !o)}
                className="flex w-full items-center justify-between px-5 py-3.5 text-left">
                <span className="text-sm font-semibold text-gray-800">{title}</span>
                <svg className={`h-4 w-4 text-gray-400 transition-transform ${open ? 'rotate-180' : ''}`}
                    fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}>
                    <path strokeLinecap="round" strokeLinejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            {open && <div className="px-5 pb-4">{children}</div>}
        </div>
    );
}

function StarRating({ value, onChange }) {
    const [hover, setHover] = useState(null);
    return (
        <div className="flex gap-1">
            {[1,2,3,4,5].map(n => (
                <button key={n} type="button"
                    onMouseEnter={() => setHover(n)} onMouseLeave={() => setHover(null)}
                    onClick={() => onChange(n)}>
                    <svg className={`h-5 w-5 transition-colors ${(hover ?? value) >= n ? 'text-yellow-400' : 'text-gray-300'}`}
                        fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </button>
            ))}
        </div>
    );
}

export default function ReviewApplication({ application, stages, org }) {
    const user = application.candidate?.user ?? {};
    const resume = application.candidate?.resumes?.[0];
    const name = candidateName(application);
    const color = avatarColor(initials(application));
    const report = application.match_report ?? {};

    const currentStageIndex = stages.findIndex(s => s.id === application.pipeline_stage_id);
    const nextStage = stages[currentStageIndex + 1] ?? null;

    const [rating, setRating] = useState(application.screening_rating ?? 0);
    const [note, setNote] = useState(application.note ?? '');
    const [savingNote, setSavingNote] = useState(false);
    const [moving, setMoving] = useState(false);
    const [rejecting, setRejecting] = useState(false);

    function saveNote() {
        setSavingNote(true);
        router.patch(`/employer/applications/${application.id}/note`, { note, rating }, {
            preserveScroll: true,
            onFinish: () => setSavingNote(false),
        });
    }

    function moveToStage(stageId) {
        setMoving(true);
        router.patch(`/employer/applications/${application.id}/move-stage`, { stage_id: stageId }, {
            onFinish: () => setMoving(false),
        });
    }

    function reject() {
        if (!confirm('Reject this application?')) return;
        setRejecting(true);
        router.patch(`/employer/applications/${application.id}/reject`, {}, {
            onFinish: () => setRejecting(false),
        });
    }

    function goBack() {
        router.get('/employer/applications');
    }

    return (
        <>
            <Head title={`Review · ${name}`} />
            <div className="flex flex-col h-screen bg-white overflow-hidden">

                {/* Fixed header 60px */}
                <header className="flex h-[60px] shrink-0 items-center gap-3 border-b border-gray-200 bg-white px-5">
                    <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-[11px] font-bold text-white"
                        style={{ background: color }}>
                        {initials(application)}
                    </div>
                    <div className="flex-1 min-w-0">
                        <p className="text-sm font-bold text-gray-900 leading-tight truncate">{name}</p>
                        <p className="text-xs text-gray-400 leading-tight truncate">
                            {user.email}
                            {application.applied_at && <> · Applied {shortDate(application.applied_at)}</>}
                            {application.job?.title && <> · {application.job.title}</>}
                        </p>
                    </div>
                    {resume?.file_path && (
                        <a href={`/storage/${resume.file_path}`} target="_blank" rel="noreferrer"
                            className="flex items-center gap-1 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 transition shrink-0">
                            <svg className="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2}>
                                <path strokeLinecap="round" strokeLinejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            CV
                        </a>
                    )}
                    <button onClick={goBack}
                        className="flex h-8 w-8 items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition shrink-0 text-lg leading-none">
                        ×
                    </button>
                </header>

                {/* Stage stepper 60px */}
                <div className="flex h-[60px] shrink-0 items-center border-b border-gray-100 bg-gray-50 px-6 w-full">
                    {stages.map((stage, i) => {
                        const past = i < currentStageIndex;
                        const current = i === currentStageIndex;
                        return (
                            <React.Fragment key={stage.id}>
                                <div className="flex flex-col items-center shrink-0">
                                    <div className={`flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold border-2 transition
                                        ${past ? 'bg-[#38009A] border-[#38009A] text-white' :
                                          current ? 'bg-white border-[#38009A] text-[#38009A]' :
                                                    'bg-white border-gray-300 text-gray-400'}`}>
                                        {past ? (
                                            <svg className="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={3}><path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        ) : i + 1}
                                    </div>
                                    <span className={`mt-0.5 text-[10px] whitespace-nowrap font-medium ${current ? 'text-[#38009A]' : 'text-gray-400'}`}>{stage.name}</span>
                                </div>
                                {i < stages.length - 1 && (
                                    <div className={`flex-1 h-px mx-2 min-w-[12px] ${i < currentStageIndex ? 'bg-[#38009A]' : 'bg-gray-300'}`} />
                                )}
                            </React.Fragment>
                        );
                    })}
                    {stages.length === 0 && (
                        <span className="text-xs text-gray-400">No pipeline stages configured for this job.</span>
                    )}
                </div>

                {/* Body: CV left + panel right */}
                <div className="flex flex-1 overflow-hidden">

                    {/* CV iframe 55% */}
                    <div className="w-[55%] shrink-0 border-r border-gray-200 bg-gray-100">
                        {resume?.file_path ? (
                            <iframe
                                src={`/storage/${resume.file_path}`}
                                className="h-full w-full"
                                title="Candidate CV"
                            />
                        ) : (
                            <div className="flex h-full flex-col items-center justify-center gap-2 text-gray-400">
                                <svg className="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={1.5}>
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p className="text-sm">No CV uploaded</p>
                            </div>
                        )}
                    </div>

                    {/* Assessment panel 45% */}
                    <div className="flex-1 overflow-y-auto bg-[#F5F5F7]">
                        <div className="bg-white rounded-xl mx-4 my-4 shadow-sm ring-1 ring-gray-100 overflow-hidden divide-y divide-gray-100">

                            {/* Candidate Info */}
                            <Accordion title="Candidate Info" defaultOpen={true}>
                                <div className="grid grid-cols-2 gap-x-4 gap-y-2 text-xs">
                                    {user.email && <div><span className="text-gray-400">Email</span><p className="text-gray-800 font-medium mt-0.5 truncate">{user.email}</p></div>}
                                    {user.phone && <div><span className="text-gray-400">Phone</span><p className="text-gray-800 font-medium mt-0.5">{user.phone}</p></div>}
                                    {application.candidate?.headline && <div className="col-span-2"><span className="text-gray-400">Headline</span><p className="text-gray-800 font-medium mt-0.5">{application.candidate.headline}</p></div>}
                                    {application.candidate?.location && <div><span className="text-gray-400">Location</span><p className="text-gray-800 font-medium mt-0.5">{application.candidate.location}</p></div>}
                                    {application.source && <div><span className="text-gray-400">Source</span><p className="text-gray-800 font-medium mt-0.5 capitalize">{application.source}</p></div>}
                                </div>
                            </Accordion>

                            {/* Match Analysis */}
                            <Accordion title="Match Analysis">
                                {application.match_score != null ? (
                                    <div className="space-y-4">
                                        {/* Score row */}
                                        <div className="flex items-center gap-3">
                                            <div className={`flex h-12 w-12 shrink-0 items-center justify-center rounded-full text-sm font-bold
                                                ${application.match_score >= 70 ? 'bg-green-100 text-green-700' :
                                                  application.match_score >= 40 ? 'bg-yellow-100 text-yellow-700' :
                                                                                  'bg-red-100 text-red-600'}`}>
                                                {application.match_score}
                                            </div>
                                            <div>
                                                <p className="text-sm font-semibold text-gray-800">CV Match Score</p>
                                                <p className="text-xs text-gray-400">
                                                    {application.match_score >= 70 ? 'Strong match' :
                                                     application.match_score >= 40 ? 'Moderate match' : 'Weak match'}
                                                </p>
                                            </div>
                                        </div>

                                        {/* Score bar */}
                                        <div className="h-1.5 w-full rounded-full bg-gray-100">
                                            <div className={`h-1.5 rounded-full transition-all ${application.match_score >= 70 ? 'bg-green-500' : application.match_score >= 40 ? 'bg-yellow-400' : 'bg-red-400'}`}
                                                style={{ width: `${application.match_score}%` }} />
                                        </div>

                                        {/* AI summary */}
                                        {report.summary && (
                                            <p className="text-xs text-gray-600 leading-relaxed border-l-2 border-gray-200 pl-2">{report.summary}</p>
                                        )}

                                        {/* Matched skills */}
                                        {report.matched_skills?.length > 0 && (
                                            <div>
                                                <p className="text-[11px] font-semibold text-green-700 mb-1.5 flex items-center gap-1">
                                                    <svg className="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2.5}><path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                    Matched ({report.matched_skills.length})
                                                </p>
                                                <div className="flex flex-wrap gap-1.5">
                                                    {report.matched_skills.map((s, i) => (
                                                        <span key={i} className="rounded-full bg-green-50 border border-green-200 px-2 py-0.5 text-[11px] font-medium text-green-700">{s}</span>
                                                    ))}
                                                </div>
                                            </div>
                                        )}

                                        {/* Missing skills */}
                                        {report.missing_skills?.length > 0 && (
                                            <div>
                                                <p className="text-[11px] font-semibold text-red-600 mb-1.5 flex items-center gap-1">
                                                    <svg className="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2.5}><path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    Missing ({report.missing_skills.length})
                                                </p>
                                                <div className="flex flex-wrap gap-1.5">
                                                    {report.missing_skills.map((s, i) => (
                                                        <span key={i} className="rounded-full bg-red-50 border border-red-200 px-2 py-0.5 text-[11px] font-medium text-red-600">{s}</span>
                                                    ))}
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                ) : (
                                    <p className="text-xs text-gray-400">No AI analysis yet. Run <span className="font-medium text-[#38009A]">AI Rank</span> from the pipeline page.</p>
                                )}
                            </Accordion>

                            {/* CV Score breakdown */}
                            {report.scores && (
                                <Accordion title="CV Score">
                                    <div className="space-y-2">
                                        {Object.entries(report.scores).map(([k, v]) => (
                                            <div key={k}>
                                                <div className="flex justify-between text-xs mb-1">
                                                    <span className="capitalize text-gray-600">{k.replace(/_/g,' ')}</span>
                                                    <span className="font-semibold text-gray-800">{v}%</span>
                                                </div>
                                                <div className="h-1.5 w-full rounded-full bg-gray-100">
                                                    <div className="h-1.5 rounded-full bg-[#38009A]" style={{ width: `${v}%` }} />
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </Accordion>
                            )}

                            {/* Screening & Notes */}
                            <Accordion title="Screening & Notes" defaultOpen={true}>
                                <div className="space-y-3">
                                    <div>
                                        <p className="text-xs text-gray-500 mb-1">Rating</p>
                                        <StarRating value={rating} onChange={setRating} />
                                    </div>
                                    <div>
                                        <p className="text-xs text-gray-500 mb-1">Notes</p>
                                        <textarea value={note} onChange={e => setNote(e.target.value)}
                                            rows={3} placeholder="Add interview notes, observations…"
                                            className="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs text-gray-700 placeholder-gray-400 focus:border-[#38009A] focus:outline-none resize-none bg-white" />
                                        <button onClick={saveNote} disabled={savingNote}
                                            className="mt-1 rounded-lg bg-[#38009A] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#1F0055] disabled:opacity-60 transition">
                                            {savingNote ? 'Saving…' : 'Save Note'}
                                        </button>
                                    </div>
                                </div>
                            </Accordion>

                            {/* AI Summary */}
                            {report.ai_summary && (
                                <Accordion title="AI Summary">
                                    <p className="text-xs text-gray-600 leading-relaxed whitespace-pre-wrap">{report.ai_summary}</p>
                                </Accordion>
                            )}

                            {/* Strengths & Gaps */}
                            {(report.strengths?.length || report.gaps?.length) && (
                                <Accordion title="Strengths & Gaps">
                                    <div className="grid grid-cols-2 gap-3">
                                        {report.strengths?.length > 0 && (
                                            <div>
                                                <p className="text-xs font-semibold text-green-700 mb-1.5">Strengths</p>
                                                <ul className="space-y-1">
                                                    {report.strengths.map((s, i) => (
                                                        <li key={i} className="flex items-start gap-1.5 text-xs text-gray-600">
                                                            <svg className="h-3.5 w-3.5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2.5}><path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                            {s}
                                                        </li>
                                                    ))}
                                                </ul>
                                            </div>
                                        )}
                                        {report.gaps?.length > 0 && (
                                            <div>
                                                <p className="text-xs font-semibold text-red-600 mb-1.5">Gaps</p>
                                                <ul className="space-y-1">
                                                    {report.gaps.map((g, i) => (
                                                        <li key={i} className="flex items-start gap-1.5 text-xs text-gray-600">
                                                            <svg className="h-3.5 w-3.5 text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth={2.5}><path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                            {g}
                                                        </li>
                                                    ))}
                                                </ul>
                                            </div>
                                        )}
                                    </div>
                                </Accordion>
                            )}

                            {/* Interview Questions */}
                            {report.interview_questions?.length > 0 && (
                                <Accordion title="Interview Questions">
                                    <ol className="space-y-2">
                                        {report.interview_questions.map((q, i) => (
                                            <li key={i} className="flex gap-2 text-xs text-gray-700">
                                                <span className="shrink-0 font-semibold text-[#38009A]">{i+1}.</span>
                                                {q}
                                            </li>
                                        ))}
                                    </ol>
                                </Accordion>
                            )}

                            {/* Screening Answers */}
                            {application.screening_answers?.length > 0 && (
                                <Accordion title="Screening Answers">
                                    <div className="space-y-3">
                                        {application.screening_answers.map((qa, i) => (
                                            <div key={i}>
                                                <p className="text-xs font-semibold text-gray-700">{qa.question}</p>
                                                <p className="text-xs text-gray-500 mt-0.5">{qa.answer ?? '—'}</p>
                                            </div>
                                        ))}
                                    </div>
                                </Accordion>
                            )}
                        </div>

                        {/* bottom spacer so last accordion clears action bar */}
                        <div className="h-20" />
                    </div>
                </div>

                {/* Fixed bottom action bar */}
                {application.status !== 'rejected' && (
                    <div className="flex h-[60px] shrink-0 items-center justify-end gap-3 border-t border-gray-200 bg-white px-5">
                        <button onClick={reject} disabled={rejecting}
                            className="rounded-lg border-2 border-red-300 px-5 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 disabled:opacity-50 transition">
                            {rejecting ? 'Rejecting…' : 'Reject'}
                        </button>
                        {nextStage ? (
                            <button onClick={() => moveToStage(nextStage.id)} disabled={moving}
                                className="rounded-lg bg-[#38009A] px-5 py-2 text-sm font-semibold text-white hover:bg-[#1F0055] disabled:opacity-50 transition">
                                {moving ? 'Moving…' : `Move to ${nextStage.name}`}
                            </button>
                        ) : currentStageIndex === -1 && stages.length > 0 ? (
                            <button onClick={() => moveToStage(stages[0].id)} disabled={moving}
                                className="rounded-lg bg-[#38009A] px-5 py-2 text-sm font-semibold text-white hover:bg-[#1F0055] disabled:opacity-50 transition">
                                {moving ? 'Moving…' : `Move to ${stages[0].name}`}
                            </button>
                        ) : (
                            <span className="rounded-lg bg-green-100 px-5 py-2 text-sm font-semibold text-green-700">Final Stage</span>
                        )}
                    </div>
                )}
                {application.status === 'rejected' && (
                    <div className="flex h-[60px] shrink-0 items-center justify-center border-t border-gray-200 bg-red-50">
                        <span className="text-sm font-semibold text-red-600">Application Rejected</span>
                    </div>
                )}
            </div>
        </>
    );
}
