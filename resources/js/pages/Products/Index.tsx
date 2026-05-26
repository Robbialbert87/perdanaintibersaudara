import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Plus, Pencil, Trash2, Package } from 'lucide-react';

interface Product {
    id: number;
    name: string;
    category: string;
    description: string | null;
    images: string[] | null;
}

export default function ProductsIndex({ products }: { products: Product[] }) {
    const handleDelete = (id: number) => {
        if (confirm('Yakin ingin menghapus produk ini?')) {
            router.delete(`/products/${id}`);
        }
    };

    return (
        <AppLayout breadcrumbs={[{ title: 'Dashboard', href: '/dashboard' }, { title: 'Kelola Produk', href: '/products' }]}>
            <Head title="Kelola Produk" />
            <div className="flex h-full flex-1 flex-col gap-4 p-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-foreground">Kelola Produk</h1>
                        <p className="text-muted-foreground text-sm">Tambah, edit, atau hapus produk yang ditampilkan di halaman utama.</p>
                    </div>
                    <Link href="/products/create">
                        <Button className="gap-2">
                            <Plus className="h-4 w-4" />
                            Tambah Produk
                        </Button>
                    </Link>
                </div>

                {products.length === 0 ? (
                    <Card>
                        <CardContent className="flex flex-col items-center justify-center py-16">
                            <Package className="h-12 w-12 text-muted-foreground mb-4" />
                            <p className="text-muted-foreground text-lg font-medium">Belum ada produk</p>
                            <p className="text-muted-foreground text-sm">Mulai tambahkan produk pertama Anda.</p>
                        </CardContent>
                    </Card>
                ) : (
                    <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        {products.map((product) => (
                            <Card key={product.id} className="overflow-hidden">
                                {product.images && product.images.length > 0 && (
                                    <div className="aspect-video overflow-hidden">
                                        <img
                                            src={`/storage/${product.images[0]}`}
                                            alt={product.name}
                                            className="h-full w-full object-cover transition-transform hover:scale-105"
                                        />
                                    </div>
                                )}
                                <CardHeader className="pb-2">
                                    <CardTitle className="text-base">{product.name}</CardTitle>
                                    <span className="inline-flex w-fit items-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary">
                                        {product.category}
                                    </span>
                                </CardHeader>
                                <CardContent>
                                    {product.description && (
                                        <p className="text-muted-foreground text-sm line-clamp-2 mb-3">{product.description}</p>
                                    )}
                                    <div className="flex gap-2">
                                        <Link href={`/products/${product.id}/edit`} className="flex-1">
                                            <Button variant="outline" size="sm" className="w-full gap-1">
                                                <Pencil className="h-3 w-3" /> Edit
                                            </Button>
                                        </Link>
                                        <Button variant="destructive" size="sm" className="gap-1" onClick={() => handleDelete(product.id)}>
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
