import { __ } from "@wordpress/i18n";
import { useState, useEffect } from '@wordpress/element';
import { SelectControl, __experimentalRadio as Radio, __experimentalRadioGroup as RadioGroup, ToggleControl } from '@wordpress/components';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';

import './editor.scss';
import ServerSideRender from "@wordpress/server-side-render";


const Edit = (props) => {

    const { attributes, setAttributes } = props;
    const { selectedDegree, selectedSubject } = attributes;

    // Zustand für die dynamischen Optionen
    const [degree, setDegree] = useState([]);
    const [subject, setSubject] = useState([]);
    const [format, setFormat] = useState( attributes.format || 'chart' );
    const [ showPercent, setShowPercent ] = useState( attributes.percent || false );

    useEffect(() => {
        if (window.sharesBlockData) {
            setDegree(window.sharesBlockData.degreeOptions || []);
            setSubject(window.sharesBlockData.subjectOptions || []);
        }
    }, []);

    return (
        <div {...useBlockProps()}>
            <InspectorControls>
                <>
                    <SelectControl
                        label={__("Degree", "fau-degree-program-shares")}
                        value={selectedDegree}
                        options={degree.map(opt => ({
                            value: opt.value,
                            label: opt.label
                        }))}
                        onChange={(value) => setAttributes({ selectedDegree: value })}
                    />
                    <SelectControl
                        label={__('Subject', 'fau-degree-program-shares')}
                        value={selectedSubject}
                        options={subject.map(opt => ({
                            value: opt.value,
                            label: opt.label
                        }))}
                        onChange={(value) => setAttributes({ selectedSubject: value })}
                    />
                    <RadioGroup
                        label={__("Format", "fau-degree-program-shares")}
                        //onChange={ setFormat }
                        onChange={ (value) => {
                            setAttributes({ format: value });
                        } }
                        checked={ format }>
                        <Radio __next40pxDefaultSize value="chart">{__("Chart", "fau-degree-program-shares")}</Radio>
                        <Radio __next40pxDefaultSize value="table">{__("Table", "fau-degree-program-shares")}</Radio>
                    </RadioGroup>
                    <ToggleControl
                        __nextHasNoMarginBottom
                        label={__("Show Percent Values", "fau-degree-program-shares")}
                        /*help={
                            showPercent
                                ? 'Has fixed background.'
                                : 'No fixed background.'
                        }*/
                        checked={ showPercent }
                        /*onChange={ (value) => {
                            setAttributes({showPercent: value} );
                        } }*/
                        onChange={ (newValue) => {
                            setShowPercent( newValue );
                        } }
                    />
                </>
            </InspectorControls>
            <ServerSideRender
                block="fau-degree-program/shares"
                attributes={attributes}
            />
        </div>
    );

};

export default Edit;
