import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Plus, Pencil, Trash2, CalendarDays } from 'lucide-react';

interface Activity {
    id: number;
    title: string;
    content: string;
    images: string[] | null;
    date: string;
}

export default function ActivitiesIndex({ activities }: { activities: Activity[] }) {
    const handleDelete = (id: number) => {
        if (confirm('Yakin ingin menghapus kegiatan ini?')) {
            router.delete(`/activities/${id}`);
        }
    };

    const formatDate = (dateStr: string) => {
        return new Date(dateStr).toLocaleDateString('id-ID', {
            day: 'numeric', month: 'long', year: 'numeric'
        });
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Dashboard', href: '/dashboard' }, { title: 'Kelola Kegiatan', href: '/activities' }]}>
            <Head title="Kelola Kegiatan" />
            <div className="flex h-full flex-1 flex-col gap-4 p-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-foreground">Kelola Kegiatan</h1>
                        <p className="text-muted-foreground text-sm">Dokumentasi dan berita kegiatan perusahaan.</p>
                    </div>
                    <Link href="/activities/create">
                        <Button className="gap-2">
                            <Plus className="h-4 w-4" />
                            Tambah Kegiatan
                        </Button>
                    </Link>
                </div>

                {activities.length === 0 ? (
                    <Card>
                        <CardContent className="flex flex-col items-center justify-center py-16">
                            <CalendarDays className="h-12 w-12 text-muted-foreground mb-4" />
                            <p className="text-muted-foreground text-lg font-medium">Belum ada kegiatan</p>
                            <p className="text-muted-foreground text-sm">Mulai dokumentasikan kegiatan pertama Anda.</p>
                        </CardContent>
                    </Card>
                ) : (
                    <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        {activities.map((activity) => (
                            <Card key={activity.id} className="overflow-hidden">
                                {activity.images && activity.images.length > 0 && (
                                    <div className="aspect-video overflow-hidden">
                                        <img
                                            src={`/storage/${activity.images[0]}`}
                                            alt={activity.title}
                                            className="h-full w-full object-cover transition-transform hover:scale-105"
                                        />
                                    </div>
                                )}
                                <CardHeader className="pb-2">
                                    <CardTitle className="text-base">{activity.title}</CardTitle>
                                    <div className="flex items-center gap-1 text-xs text-muted-foreground">
                                        <CalendarDays className="h-3 w-3" />
                                        {formatDate(activity.date)}
                                    </div>
                                </CardHeader>
                                <CardContent>
                                    <p className="text-muted-foreground text-sm line-clamp-3 mb-3">{activity.content}</p>
                                    <div className="flex gap-2">
                                        <Link href={`/activities/${activity.id}/edit`} className="flex-1">
                                            <Button variant="outline" size="sm" className="w-full gap-1">
                                                <Pencil className="h-3 w-3" /> Edit
                                            </Button>
                                        </Link>
                                        <Button variant="destructive" size="sm" className="gap-1" onClick={() => handleDelete(activity.id)}>
                                            <Trash2 className="h-3 w-3" /> Hapus
                                        </Button>
                                    </div>
                                </CardContent>
                            </Card>
                        ))}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
