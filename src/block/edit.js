import { __ } from "@wordpress/i18n";
import { useState, useEffect } from '@wordpress/element';
import { SelectControl } from '@wordpress/components';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';

import './editor.scss';


const Edit = (props) => {

    const { attributes, setAttributes } = props;
    const { selectedDegree, selectedSubject } = attributes;
    const [format, setFormat] = useState( attributes.format || 'chart' );

    // Zustand für die dynamischen Optionen
    const [degree, setDegree] = useState([]);
    const [subject, setSubject] = useState([]);

    // Daten aus PHP abrufen (aus window.sharesBlockData)
    useEffect(() => {
        if (window.sharesBlockData) {
            // Daten in die beiden State-Variablen speichern
            setDegree(window.sharesBlockData.degreeOptions || []);
            setSubject(window.sharesBlockData.subjectOptions || []);
        }
    }, []);

    return (
        <div {...useBlockProps()}>
            <InspectorControls>
                <>
                    <SelectControl
                        label="Wähle eine Option 1"
                        value={selectedDegree}
                        options={degree.map(opt => ({
                            value: opt.value,
                            label: opt.label
                        }))}
                        onChange={(value) => setAttributes({ selectedDegree: value })}
                    />
                    <SelectControl
                        label="Wähle eine Option 2"
                        value={selectedSubject}
                        options={subject.map(opt => ({
                            value: opt.value,
                            label: opt.label
                        }))}
                        onChange={(value) => setAttributes({ selectedSubject: value })}
                    />
                </>
            </InspectorControls>
            <p>Auswahl 1: {selectedDegree}</p>
            <p>Auswahl 2: {selectedSubject}</p>
        </div>
    );

};

export default Edit;
