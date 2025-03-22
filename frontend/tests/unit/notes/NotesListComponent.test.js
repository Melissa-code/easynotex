import { describe, it, expect } from 'vitest';
import { render, screen } from '../../../node_modules/@testing-library/vue';
import { mount } from '../../../node_modules/@vue/test-utils';
import NotesListComponent from '../../../src/components/notes/NotesListComponent.vue';
import NoteCardComponent from '../../../src/components/notes/NoteCardComponent.vue';

describe('NotesListComponent', () => {
    
    it('devrait rendre le bon nombre de notes', () => {
        const notes = [
            { id: 1, title: 'Note 1', content: 'Contenu 1' },
            { id: 2, title: 'Note 2', content: 'Contenu 2' },
            { id: 3, title: 'Note 3', content: 'Contenu 3' },
        ];
        
        const wrapper = mount(NotesListComponent, {
            props: { notes }
        });
        const noteCards = wrapper.findAllComponents(NoteCardComponent);
        
        expect(noteCards.length).toBe(notes.length);
    });

    it('devrait gérer le cas où il n’y a pas de notes', () => {
        const wrapper = mount(NotesListComponent, {
            props: { notes: [] }
        });

        expect(wrapper.findAllComponents(NoteCardComponent).length).toBe(0);
    });

    it('devrait générer une clé unique pour chaque note', () => {
        const notes = [
            { id: 1, title: 'Note 1', content: 'contenu 1' },
            { id: 2, title: 'Note 2', content: 'contenu 2' },
            { id: 3, title: 'Note 3', content: 'contenu 3' },
        ];
        
        const wrapper = mount(NotesListComponent, {
            props: { notes }
        });
        
        //Check NoteCardComponent
        const noteCards = wrapper.findAllComponents(NoteCardComponent);
        expect(noteCards.length).toBe(notes.length);

        //Check props note
        noteCards.forEach((card, index) => {
            expect(card.props('note')).toEqual(notes[index]);
        });
    });

    it('devrait mettre à jour l’affichage lorsque les notes changent', async () => {
        const wrapper = mount(NotesListComponent, {
            props: { notes: [{ id: 1, title: 'Note 1', content: 'Contenu 1' }] }
        });
    
        expect(wrapper.findAllComponents(NoteCardComponent).length).toBe(1);
    
        //update props
        await wrapper.setProps({ notes: [
            { id: 1, title: 'Note 1', content: 'Contenu 1' },
            { id: 2, title: 'Note 2', content: 'Contenu 2' }
        ]});
    
        expect(wrapper.findAllComponents(NoteCardComponent).length).toBe(2);
    });

    it('devrait ne pas poser de bug si la prop notes est undefined', () => {
        const wrapper = mount(NotesListComponent, {
            props: { notes: undefined }
        });
    
        expect(wrapper.findAllComponents(NoteCardComponent).length).toBe(0);
    });

    it('devrait contenir la classe principale du layout', () => {
        const wrapper = mount(NotesListComponent, {
            props: { notes: [] }
        });
    
        expect(wrapper.classes()).toContain('mx-4'); 
    });
    
    it('devrait afficher les titres et contenus des notes', async () => {
        const notes = [
            { id: 1, title: 'Note 1', content: 'Contenu 1' },
            { id: 2, title: 'Note 2', content: 'Contenu 2' }
        ];

        await render(NotesListComponent, { props: { notes } });
    
        expect(await screen.findByText('NOTE 1')).toBeTruthy();
        expect(await screen.findByText('Contenu 1')).toBeTruthy();
        expect(await screen.findByText('NOTE 2')).toBeTruthy();
        expect(await screen.findByText('Contenu 2')).toBeTruthy();
    }); 
});
