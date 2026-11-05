import JobifyLayout from '../../../Layouts/JobifyLayout';

const roleColors = { admin: 'bg-indigo-50 text-indigo-700', hiring_manager: 'bg-blue-50 text-blue-700', recruiter: 'bg-green-50 text-green-700', viewer: 'bg-gray-100 text-gray-600' };

export default function Team({ org, team }) {
    return (
        <JobifyLayout title="Team Members">
            <div className="max-w-3xl space-y-4">
                <div className="bg-white rounded-xl border border-gray-200">
                    <div className="p-5 border-b border-gray-100 flex items-center justify-between">
                        <h2 className="font-semibold text-gray-900">Team ({team?.length ?? 0})</h2>
                    </div>
                    {team?.length > 0 ? (
                        <div className="divide-y divide-gray-100">
                            {team.map(member => (
                                <div key={member.id} className="px-5 py-4 flex items-center justify-between">
                                    <div className="flex items-center gap-3">
                                        <div className="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm">
                                            {(member.user?.first_name || member.user?.name || 'U')[0].toUpperCase()}
                                        </div>
                                        <div>
                                            <div className="font-medium text-gray-900 text-sm">{member.user?.first_name} {member.user?.last_name}</div>
                                            <div className="text-gray-500 text-xs">{member.user?.email}</div>
                                        </div>
                                    </div>
                                    <span className={`text-xs px-2 py-1 rounded-full font-medium capitalize ${roleColors[member.role] ?? 'bg-gray-100 text-gray-600'}`}>{member.role?.replace('_',' ')}</span>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="p-8 text-center text-gray-400 text-sm">No team members yet</div>
                    )}
                </div>
            </div>
        </JobifyLayout>
    );
}
