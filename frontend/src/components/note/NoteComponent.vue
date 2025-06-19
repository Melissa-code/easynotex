<script>
import Swal from 'sweetalert2';
import axios from 'axios';

export default {
    name: 'NoteComponent',
    //data from parent
    props: {
        note: Object,
        loading: {
            type: Boolean,
            required: false,
        },
        default: () => ({
            title: "",
            content: "",
            image: null,
            updated_at: "",
            created_at: "",
            category_name: "",
            isFavorite: 0,
        })
    },
    //applique le zoom si l’image arrive plus tard
    watch: {
        'note.image'(newValue) {
            if (newValue) {
                // on attend que le DOM soit bien mis à jour
                this.$nextTick(() => {
                    import('medium-zoom').then(({ default: mediumZoom }) => {
                        mediumZoom('.zoomable')
                    })
                })
            }
        }
    },
    data() {
        return {
            imageExists: true
        }
    },
    //applique le zoom si l’image est déjà là
    mounted() {
        if (this.note.image) {
            this.$nextTick(() => {
                import('medium-zoom').then(({ default: mediumZoom }) => {
                    mediumZoom('.zoomable')
                })
            })
        }
    },
    computed: {
        truncatedTitle() {
            if (!this.note || !this.note.title) {
                return '';
            }
            return this.truncate(this.note.title.toUpperCase(), 50);
        },
        displayedDate() {
            if (!this.note) {
                return '';
            }
            return new Date(this.note.updated_at) > new Date(this.note.created_at)
                ? `Modifiée le ${this.formatDate(this.note.updated_at)}`
                : `Créée le ${this.formatDate(this.note.created_at)}`;
        }
    },
    methods: {
        formatDate(dateString) {
            const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
            return new Date(dateString).toLocaleDateString('fr-FR', options);
        },
        truncate(text = "", maxlength) {
            return text.length > maxlength ? text.slice(0, maxlength - 1) + '…' : text;
        },
        displayFavorite() {
            return `
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="size-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                </svg>
            `;
        },
        confirmDeleteNote(noteId) {
            Swal.fire({
                title: 'Êtes-vous sûr de vouloir supprimer cette note ?',
                text: "Vous ne pourrez pas revenir en arrière !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1A535C',
                cancelButtonColor: '#FF6B6B',
                confirmButtonText: 'Oui, supprimer'
            }).then((result) => {
                if (result.isConfirmed) {
                this.deleteNote(noteId);
                }
            });
        },
        async deleteNote(noteId) {
            try {
                await axios.delete(`${import.meta.env.VITE_API_URL}/api/notes/supprime_note/${noteId}`);
                Swal.fire(
                    'Supprimé !',
                    'Votre note a été supprimée.',
                    'success'
                );
                // Rediriger vers la page d'accueil ou la liste des notes
                this.$router.push('/');
            } catch (error) {
                console.error('Erreur lors de la suppression de la note:', error);
                Swal.fire(
                    'Erreur !',
                    'Une erreur est survenue lors de la suppression de la note.',
                    'error'
                );
            }
        }
    }
};
</script>

<template>
    <div class="container mx-auto px-4">
        <div class="bg-white rounded-2xl border border-[--dark-green] overflow-hidden" v-if="note && !loading">
            <!-- Title -->
            <div class="p-4 border-b border-b-[--dark-green] bg-[--yellow-light] text-center">
                <h3 class="text-[--dark-green]">{{ truncatedTitle }}</h3>
            </div>
            <!-- Infos -->
            <div class="p-4">
                <ul>
                    <!-- date -->
                    <li class="text-sm mb-4">{{ displayedDate }}</li>
                    <!-- category & favorite -->
                    <li class="mb-4 font-semibold flex justify-between">
                        <span>Catégorie : {{ note?.category_name || '' }}</span>
                        <span v-if="note && note.isFavorite === 1" class="favorite-icon"
                            v-html="displayFavorite()"></span>
                    </li>
                    <!-- content -->
                    <li class="text-justify pt-3">{{ note?.content || '' }}</li>
                    <!-- image -->
                    <li class="py-5" v-if="note.image">
                        <!-- @error pour détecter une erreur de chargement d’image -->
                        <img 
                            v-if="imageExists" 
                            :src="'/uploads/' + note.image" 
                            :alt="'Image de la note n°' + note.id"
                            class="w-32 h-auto cursor-pointer zoomable"
                            @error="imageExists = false" />
                    </li>

                    <li class="flex white gap-1">
                        <!-- Back to list -->
                        <button type="button" class="rounded-full">
                            <Router-link to="/">
                                <span class="text-white">Retour</span>
                            </Router-link>
                        </button>
                        <!-- Edit note -->
                        <button type="button" class="rounded-full">
                            <i class="fas fa-pen fa-sm" style="color: #ffffff;"></i>
                        </button>
                        <!-- Delete note -->
                        <button @click="confirmDeleteNote(note.id)" type="button" class="rounded-full">
                            <i class="fas fa-trash-alt fa-sm" style="color: #ffffff;"></i>
                        </button>
                        <!-- Download PDF -->
                        <button type="button" class="rounded-full">
                            <i class="fas fa-file-pdf fa-sm" style="color: #ffffff;"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<style scoped>

.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 80vh;
}

.bg-white {
    max-width: 500px;
    width: 100%;
    /* // responsive */

}

.favorite-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: var(--dark-green);
}
</style>