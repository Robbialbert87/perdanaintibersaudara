import { Head, useForm, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ArrowLeft, Upload, X } from 'lucide-react';
import { FormEvent, useRef, useState } from 'react';

interface Product {
    id: number;
    name: string;
    category: string;
    description: string | null;
    images: string[] | null;
}

const CATEGORIES = ['Radiologi', 'Laboratorium', 'Sparepart & Elektromedis'];
const MAX_IMAGES = 5;

export default function ProductForm({ product }: { product?: Product }) {
    const isEdit = !!product;
    const fileInputRef = useRef<HTMLInputElement>(null);
    
    const [existingImages, setExistingImages] = useState<string[]>(product?.images || []);
    const [newImages, setNewImages] = useState<{ file: File; preview: string }[]>([]);

    const { data, setData, post, processing, errors, transform } = useForm({
        name: product?.name || '',
        category: product?.category || CATEGORIES[0],
        description: product?.description || '',
        kept_images: product?.images || [],
        images: [] as File[],
        _method: isEdit ? 'PUT' : undefined,
    });

    transform((data) => ({
        ...data,
        images: newImages.map(img => img.file),
        kept_images: existingImages,
    }));

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        
        if (isEdit) {
            post(`/products/${product.id}`, { forceFormData: true });
        } else {
            post('/products', { forceFormData: true });
        }
    };

    const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const files = Array.from(e.target.files || []);
        if (!files.length) return;

        const totalCurrentImages = existingImages.length + newImages.length;
        const availableSlots = MAX_IMAGES - totalCurrentImages;
        
        if (availableSlots <= 0) {
            alert(`Maksimal ${MAX_IMAGES} foto diperbolehkan.`);
            return;
        }

        const filesToAdd = files.slice(0, availableSlots);
        
        const newImagesWithPreview = filesToAdd.map(file => ({
            file,
            preview: URL.createObjectURL(file)
        }));

        setNewImages(prev => [...prev, ...newImagesWithPreview]);
        
        // Reset file input
        if (fileInputRef.current) {
            fileInputRef.current.value = '';
        }
    };

    const removeExistingImage = (index: number) => {
        setExistingImages(prev => prev.filter((_, i) => i !== index));
    };

    const removeNewImage = (index: number) => {
        setNewImages(prev => {
            const updated = [...prev];
            URL.revokeObjectURL(updated[index].preview);
            updated.splice(index, 1);
            return updated;
        });
    };

    const totalImages = existingImages.length + newImages.length;

    return (
        <AppLayout breadcrumbs={[
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Kelola Produk', href: '/products' },
            { title: isEdit ? 'Edit Produk' : 'Tambah Produk', href: '#' },
        ]}>
            <Head title={isEdit ? 'Edit Produk' : 'Tambah Produk'} />
            <div className="flex h-full flex-1 flex-col gap-4 p-4">
                <div className="flex items-center gap-4">
                    <Link href="/products">
                        <Button variant="ghost" size="sm" className="gap-1">
                            <ArrowLeft className="h-4 w-4" /> Kembali
                        </Button>
                    </Link>
                    <h1 className="text-2xl font-bold text-foreground">
                        {isEdit ? 'Edit Produk' : 'Tambah Produk Baru'}
                    </h1>
                </div>

                <Card className="max-w-2xl">
                    <CardHeader>
                        <CardTitle>Informasi Produk</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSubmit} className="space-y-6">
                            <div className="space-y-2">
                                <Label htmlFor="name">Nama Produk</Label>
                                <Input
                                    id="name"
                                    value={data.name}
                                    onChange={e => setData('name', e.target.value)}
                                    placeholder="Contoh: Digital X-Ray System"
                                />
                                {errors.name && <p className="text-sm text-destructive">{errors.name}</p>}
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="category">Kategori</Label>
                                <select
                                    id="category"
                                    value={data.category}
                                    onChange={e => setData('category', e.target.value)}
                                    className="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                                >
                                    {CATEGORIES.map(cat => (
                                        <option key={cat} value={cat}>{cat}</option>
                                    ))}
                                </select>
                                {errors.category && <p className="text-sm text-destructive">{errors.category}</p>}
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="description">Deskripsi (opsional)</Label>
                                <textarea
                                    id="description"
                                    rows={4}
                                    value={data.description}
                                    onChange={e => setData('description', e.target.value)}
                                    placeholder="Deskripsi singkat produk..."
                                    className="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                                />
                                {errors.description && <p className="text-sm text-destructive">{errors.description}</p>}
                            </div>

                            <div className="space-y-2">
                                <div className="flex justify-between items-center mb-2">
                                    <Label>Foto Produk (Maksimal 5)</Label>
                                    <span className="text-xs text-muted-foreground">{totalImages} / 5 diunggah</span>
                                </div>
                                
                                <div className="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                                    {/* Existing Images */}
                                    {existingImages.map((imgPath, index) => (
                                        <div key={`existing-${index}`} className="relative group rounded-md border overflow-hidden aspect-square">
                                            <img src={`/storage/${imgPath}`} alt="Preview" className="h-full w-full object-cover" />
                                            <button 
                                                type="button" 
                                                onClick={() => removeExistingImage(index)}
                                                className="absolute top-1 right-1 bg-destructive/90 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                                            >
                                                <X className="h-4 w-4" />
                                            </button>
                                        </div>
                                    ))}

                                    {/* New Images Preview */}
                                    {newImages.map((img, index) => (
                                        <div key={`new-${index}`} className="relative group rounded-md border overflow-hidden aspect-square">
                                            <img src={img.preview} alt="Preview" className="h-full w-full object-cover" />
                                            <div className="absolute top-1 left-1 bg-primary text-primary-foreground text-[10px] px-1.5 py-0.5 rounded shadow">Baru</div>
                                            <button 
                                                type="button" 
                                                onClick={() => removeNewImage(index)}
                                                className="absolute top-1 right-1 bg-destructive/90 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                                            >
                                                <X className="h-4 w-4" />
                                            </button>
                                        </div>
                                    ))}

                                    {/* Upload Button */}
                                    {totalImages < MAX_IMAGES && (
                                        <div
                                            className="relative flex flex-col items-center justify-center rounded-lg border-2 border-dashed border-input p-4 cursor-pointer hover:border-primary hover:bg-muted/50 transition-colors aspect-square"
                                            onClick={() => fileInputRef.current?.click()}
                                        >
                                            <Upload className="h-6 w-6 text-muted-foreground mb-2" />
                                            <span className="text-xs text-muted-foreground text-center">Tambah Foto</span>
                                        </div>
                                    )}
                                </div>
                                
                                <input
                                    ref={fileInputRef}
                                    type="file"
                                    accept="image/*"
                                    multiple
                                    className="hidden"
                                    onChange={handleImageChange}
                                />
                                {errors.images && <p className="text-sm text-destructive">{errors.images}</p>}
                                {errors['images.0'] && <p className="text-sm text-destructive">{errors['images.0']}</p>}
                            </div>

                            <Button type="submit" disabled={processing} className="w-full">
                                {processing ? 'Menyimpan...' : (isEdit ? 'Perbarui Produk' : 'Simpan Produk')}
                            </Button>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
